<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(Request $request)
    {
        // Only retrieve active pricing keys
        $settings = Setting::whereIn('key', ['price_monthly', 'price_yearly', 'price_addon_slot'])
            ->pluck('value', 'key')
            ->toArray();

        // Fill dynamic pricing defaults if not set in database
        $settings['price_monthly'] = (int)($settings['price_monthly'] ?? 30000);
        $settings['price_yearly'] = (int)($settings['price_yearly'] ?? 300000);
        $settings['price_addon_slot'] = (int)($settings['price_addon_slot'] ?? 30000);

        $priceMonthly = $settings['price_monthly'];
        $priceYearly = $settings['price_yearly'];

        $usersMonthlyCount = User::where('role', 'pharmacist')
            ->where('payment_status', 'paid')
            ->where('subscription_plan', 'monthly')
            ->count();
        $usersYearlyCount = User::where('role', 'pharmacist')
            ->where('payment_status', 'paid')
            ->where('subscription_plan', 'yearly')
            ->count();

        $totalRevenue = ($usersMonthlyCount * $priceMonthly) + ($usersYearlyCount * $priceYearly);
        
        $activePharmacies = User::where('role', 'pharmacist')
            ->where('payment_status', 'paid')
            ->count();

        $pendingApprovals = User::where('role', 'pharmacist')
            ->whereNotNull('payment_receipt')
            ->where('payment_status', 'pending')
            ->count();

        // Fetch bank name and account number separately to pass to view
        $bankName = Setting::where('key', 'bank_name')->value('value') ?? '';
        $accountNumber = Setting::where('key', 'account_number')->value('value') ?? '';

        // Fetch registered pharmacists with search and filter
        $usersQuery = User::where('role', 'pharmacist');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $usersQuery->where('payment_status', $request->input('status'));
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.dashboard', compact('totalRevenue', 'activePharmacies', 'pendingApprovals', 'users', 'settings', 'bankName', 'accountNumber'));
    }

    /**
     * Update B2B Payment settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'monthly_fee' => 'nullable|integer|min:0',
            'price_monthly' => 'nullable|integer|min:0',
            'price_yearly' => 'nullable|integer|min:0',
            'price_addon_slot' => 'nullable|integer|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            foreach ($validated as $key => $value) {
                if ($value !== null) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            }
        });

        return redirect()->back()->with('success', 'Pengaturan pembayaran B2B berhasil diperbarui.');
    }

    /**
     * Approve user payment subscription.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // Delete receipt file if exists to save space
            if ($user->payment_receipt) {
                $oldPath = str_replace('storage/', '', $user->payment_receipt);
                Storage::disk('public')->delete($oldPath);
                $user->payment_receipt = null;
            }

            $user->payment_status = 'paid';

            $plan = $user->pending_plan ?? 'monthly';
            $addonQty = (int)$user->pending_addon_qty;

            if ($plan === 'monthly') {
                $currentEnd = $user->subscription_ends_at;
                if (!$currentEnd || $currentEnd->isPast()) {
                    $user->subscription_ends_at = now()->addMonth();
                } else {
                    $user->subscription_ends_at = $currentEnd->addMonth();
                }
                $user->subscription_plan = 'monthly';
            } elseif ($plan === 'yearly') {
                $currentEnd = $user->subscription_ends_at;
                if (!$currentEnd || $currentEnd->isPast()) {
                    $user->subscription_ends_at = now()->addYear();
                } else {
                    $user->subscription_ends_at = $currentEnd->addYear();
                }
                $user->subscription_plan = 'yearly';

                // Bonus Logic: If yearly_bonus_claimed is false, add 50 to max_slots and set yearly_bonus_claimed = true.
                if (!$user->yearly_bonus_claimed) {
                    $user->max_slots = ($user->max_slots ?? 50) + 50;
                    $user->yearly_bonus_claimed = true;
                }
            }

            // If they bought Add-on slots: Add the approved addon_qty to the user's max_slots.
            if ($addonQty > 0) {
                $user->max_slots = ($user->max_slots ?? 50) + $addonQty;
            }

            // Reset pending request details
            $user->pending_plan = null;
            $user->pending_addon_qty = 0;
            $user->save();
        });

        return redirect()->back()->with('success', "Pembayaran untuk apoteker {$user->name} berhasil disetujui.");
    }

    /**
     * Reject user payment subscription.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // Delete receipt file if exists to save space
            if ($user->payment_receipt) {
                $oldPath = str_replace('storage/', '', $user->payment_receipt);
                Storage::disk('public')->delete($oldPath);
                $user->payment_receipt = null;
            }

            $user->payment_status = 'rejected';
            $user->pending_plan = null;
            $user->pending_addon_qty = 0;
            $user->save();
        });

        return redirect()->back()->with('success', "Pembayaran untuk apoteker {$user->name} telah ditolak.");
    }
}

