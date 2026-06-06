<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubscriptionBillingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SettingSeeder::class);
    }

    /**
     * Test guest cannot access dashboard or billing.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('billing.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test unpaid pharmacist is redirected to billing.
     */
    public function test_unpaid_pharmacist_is_redirected_to_billing(): void
    {
        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($pharmacist)->get(route('dashboard'));
        $response->assertRedirect(route('billing.index'));

        $response = $this->actingAs($pharmacist)->get(route('inventory'));
        $response->assertRedirect(route('billing.index'));
    }

    /**
     * Test pharmacist can view billing page.
     */
    public function test_pharmacist_can_view_billing_page(): void
    {
        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($pharmacist)->get(route('billing.index'));
        $response->assertStatus(200);
        $response->assertSee('Informasi Pembayaran');
    }

    /**
     * Test pharmacist can upload receipt.
     */
    public function test_pharmacist_can_upload_receipt(): void
    {
        Storage::fake('public');

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($pharmacist)->post(route('billing.upload'), [
            'payment_receipt' => $file,
        ]);

        $response->assertRedirect(route('billing.index'));
        $this->assertEquals('pending', $pharmacist->fresh()->payment_status);
        $this->assertNotNull($pharmacist->fresh()->payment_receipt);

        // Check file exists in fake storage
        $storedPath = str_replace('storage/', '', $pharmacist->fresh()->payment_receipt);
        Storage::disk('public')->assertExists($storedPath);
    }

    /**
     * Test paid pharmacist can access dashboard and inventory.
     */
    public function test_paid_pharmacist_can_access_dashboard_and_inventory(): void
    {
        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'paid',
            'subscription_ends_at' => now()->addMonth(),
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($pharmacist)->get(route('dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($pharmacist)->get(route('inventory'));
        $response->assertStatus(200);
    }

    /**
     * Test admin can access admin dashboard and not 403.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    /**
     * Test pharmacist cannot access admin dashboard.
     */
    public function test_pharmacist_cannot_access_admin_dashboard(): void
    {
        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($pharmacist)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test admin can approve pharmacist payment.
     */
    public function test_admin_can_approve_pharmacist_payment(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'payment_receipt' => 'storage/receipts/test.jpg',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.approve', $pharmacist->id));

        $response->assertRedirect();
        $this->assertEquals('paid', $pharmacist->fresh()->payment_status);
        $this->assertNotNull($pharmacist->fresh()->subscription_ends_at);
    }

    /**
     * Test admin can reject pharmacist payment.
     */
    public function test_admin_can_reject_pharmacist_payment(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'payment_receipt' => 'storage/receipts/test.jpg',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.reject', $pharmacist->id));

        $response->assertRedirect();
        $this->assertEquals('rejected', $pharmacist->fresh()->payment_status);
        $this->assertNull($pharmacist->fresh()->payment_receipt);
    }

    /**
     * Test expired pharmacist subscription is automatically revoked and redirected to billing.
     */
    public function test_expired_pharmacist_is_revoked_and_redirected_to_billing(): void
    {
        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'paid',
            'payment_receipt' => 'storage/receipts/test.jpg',
            'subscription_ends_at' => now()->subDay(), // Expired 1 day ago
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($pharmacist)->get(route('dashboard'));

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('error', 'Masa langganan Anda telah habis. Silakan lakukan pembayaran untuk memperpanjang akses.');

        $pharmacist = $pharmacist->fresh();
        $this->assertEquals('pending', $pharmacist->payment_status);
        $this->assertNull($pharmacist->payment_receipt);
    }

    /**
     * Test admin can update B2B settings.
     */
    public function test_admin_can_update_b2b_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'bank_name' => 'Bank Mandiri',
            'account_number' => '87654321',
            'account_name' => 'MedLogix Mandiri',
            'monthly_fee' => 75000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key' => 'bank_name',
            'value' => 'Bank Mandiri',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'monthly_fee',
            'value' => '75000',
        ]);
    }

    /**
     * Test admin can search pharmacists by name or email.
     */
    public function test_admin_can_search_pharmacists(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $pharmacist1 = User::factory()->create([
            'name' => 'Alice B2B',
            'email' => 'alice@medlogix.test',
            'role' => 'pharmacist',
            'sipa' => 'SIPA-111',
        ]);

        $pharmacist2 = User::factory()->create([
            'name' => 'Bob B2B',
            'email' => 'bob@medlogix.test',
            'role' => 'pharmacist',
            'sipa' => 'SIPA-222',
        ]);

        // Search by name
        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['search' => 'Alice']));
        $response->assertStatus(200);
        $response->assertSee('Alice B2B');
        $response->assertDontSee('Bob B2B');

        // Search by email
        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['search' => 'bob@']));
        $response->assertStatus(200);
        $response->assertSee('Bob B2B');
        $response->assertDontSee('Alice B2B');
    }

    /**
     * Test admin can filter pharmacists by payment status.
     */
    public function test_admin_can_filter_pharmacists_by_payment_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $paidPharmacist = User::factory()->create([
            'name' => 'Paid Pharmacist',
            'role' => 'pharmacist',
            'payment_status' => 'paid',
            'sipa' => 'SIPA-PAID',
        ]);

        $pendingPharmacist = User::factory()->create([
            'name' => 'Pending Pharmacist',
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'sipa' => 'SIPA-PENDING',
        ]);

        // Filter by paid
        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['status' => 'paid']));
        $response->assertStatus(200);
        $response->assertSee('Paid Pharmacist');
        $response->assertDontSee('Pending Pharmacist');

        // Filter by pending
        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['status' => 'pending']));
        $response->assertStatus(200);
        $response->assertSee('Pending Pharmacist');
        $response->assertDontSee('Paid Pharmacist');
    }

    /**
     * Test registration starts 7-day trial.
     */
    public function test_registration_starts_7_day_trial(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Apoteker Trial',
            'username' => 'apotekertrial',
            'email' => 'trial@medlogix.test',
            'phone' => '081234567890',
            'sipa' => 'SIPA-TRIAL-123',
            'apotek_address' => 'Apotek Trial Sentosa',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'trial@medlogix.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('paid', $user->payment_status);
        $this->assertEquals('trial', $user->subscription_plan);
        $this->assertEquals(50, $user->max_slots);
        $this->assertFalse($user->yearly_bonus_claimed);
        $this->assertNotNull($user->subscription_ends_at);
        $this->assertTrue($user->subscription_ends_at->isFuture());
    }

    /**
     * Test pharmacist can upload receipt with plan and addon_qty.
     */
    public function test_pharmacist_can_upload_receipt_with_plan_and_addons(): void
    {
        Storage::fake('public');

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($pharmacist)->post(route('billing.upload'), [
            'payment_receipt' => $file,
            'pending_plan' => 'yearly',
            'pending_addon_qty' => 20,
        ]);

        $response->assertRedirect(route('billing.index'));
        $pharmacist = $pharmacist->fresh();
        $this->assertEquals('pending', $pharmacist->payment_status);
        $this->assertEquals('yearly', $pharmacist->pending_plan);
        $this->assertEquals(20, $pharmacist->pending_addon_qty);
        $this->assertNotNull($pharmacist->payment_receipt);
    }

    /**
     * Test admin approval logic for monthly plan and addon slots.
     */
    public function test_admin_approves_monthly_plan_and_addons(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'payment_receipt' => 'storage/receipts/test.jpg',
            'pending_plan' => 'monthly',
            'pending_addon_qty' => 10,
            'max_slots' => 50,
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.approve', $pharmacist->id));

        $response->assertRedirect();
        
        $pharmacist = $pharmacist->fresh();
        $this->assertEquals('paid', $pharmacist->payment_status);
        $this->assertEquals('monthly', $pharmacist->subscription_plan);
        $this->assertEquals(60, $pharmacist->max_slots); // 50 + 10 addon
        $this->assertNull($pharmacist->pending_plan);
        $this->assertEquals(0, $pharmacist->pending_addon_qty);
        $this->assertNotNull($pharmacist->subscription_ends_at);
        $this->assertTrue($pharmacist->subscription_ends_at->isFuture());
    }

    /**
     * Test admin approval logic for yearly plan with first-time bonus slots.
     */
    public function test_admin_approves_yearly_plan_with_first_time_bonus(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Office',
        ]);

        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'pending',
            'payment_receipt' => 'storage/receipts/test.jpg',
            'pending_plan' => 'yearly',
            'pending_addon_qty' => 0,
            'max_slots' => 50,
            'yearly_bonus_claimed' => false,
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.approve', $pharmacist->id));

        $response->assertRedirect();

        $pharmacist = $pharmacist->fresh();
        $this->assertEquals('paid', $pharmacist->payment_status);
        $this->assertEquals('yearly', $pharmacist->subscription_plan);
        $this->assertEquals(100, $pharmacist->max_slots); // 50 + 50 bonus
        $this->assertTrue($pharmacist->yearly_bonus_claimed);
        $this->assertNull($pharmacist->pending_plan);
    }

    /**
     * Test slot limit enforcement.
     */
    public function test_slot_limit_enforcement(): void
    {
        $pharmacist = User::factory()->create([
            'role' => 'pharmacist',
            'payment_status' => 'paid',
            'subscription_ends_at' => now()->addMonth(),
            'max_slots' => 2,
            'sipa' => 'SIPA-123',
            'apotek_address' => 'Apotek Test',
        ]);

        // Create 2 medicines
        \App\Models\Medicine::create([
            'nama_obat' => 'Obat A',
            'brand' => 'Brand A',
            'tanggal_masuk' => '2026-06-06',
            'no_batch' => 'BATCH-A',
            'stok' => 10,
            'exp_date' => '2027-06-06',
            'harga' => 1000,
            'informasi_general' => 'Alert: Info A',
            'alert_level' => 'info',
            'user_id' => $pharmacist->id,
        ]);

        \App\Models\Medicine::create([
            'nama_obat' => 'Obat B',
            'brand' => 'Brand B',
            'tanggal_masuk' => '2026-06-06',
            'no_batch' => 'BATCH-B',
            'stok' => 10,
            'exp_date' => '2027-06-06',
            'harga' => 1000,
            'informasi_general' => 'Alert: Info B',
            'alert_level' => 'info',
            'user_id' => $pharmacist->id,
        ]);

        // Attempt to create 3rd medicine
        $response = $this->actingAs($pharmacist)->post(route('inventory.store'), [
            'nama_obat' => 'Obat C',
            'brand' => 'Brand C',
            'tanggal_masuk' => '2026-06-06',
            'no_batch' => 'BATCH-C',
            'stok' => 10,
            'exp_date' => '2027-06-06',
            'harga' => 1000,
            'informasi_general' => 'Info C',
            'alert_level' => 'info',
        ]);

        $response->assertStatus(403);
    }
}

