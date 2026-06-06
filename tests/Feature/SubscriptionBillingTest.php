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
}

