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
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $paidUsersCount = User::where('role', 'pharmacist')->where('payment_status', 'paid')->count();
        $monthlyFee = (int)($settings['monthly_fee'] ?? 50000);
        $totalRevenue = $paidUsersCount * $monthlyFee;
        $activePharmacies = $paidUsersCount;
        $pendingApprovals = User::where('role', 'pharmacist')
            ->whereNotNull('payment_receipt')
            ->where('payment_status', 'pending')
            ->count();

        // Fetch registered pharmacists
        $users = User::where('role', 'pharmacist')->get();

        return view('admin.dashboard', compact('totalRevenue', 'activePharmacies', 'pendingApprovals', 'users', 'settings'));
    }

    /**
     * Update B2B Payment settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:255',
            'monthly_fee' => 'required|integer|min:0',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan pembayaran B2B berhasil diperbarui.');
    }

    /**
     * Approve user payment subscription.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->payment_status = 'paid';
        $user->subscription_ends_at = now()->addMonth(); // 1 month subscription
        $user->save();

        return redirect()->back()->with('success', "Pembayaran untuk apoteker {$user->name} berhasil disetujui.");
    }

    /**
     * Reject user payment subscription.
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);

        // Delete receipt file if exists to save space
        if ($user->payment_receipt) {
            $oldPath = str_replace('storage/', '', $user->payment_receipt);
            Storage::disk('public')->delete($oldPath);
            $user->payment_receipt = null;
        }

        $user->payment_status = 'rejected';
        $user->save();

        return redirect()->back()->with('success', "Pembayaran untuk apoteker {$user->name} telah ditolak.");
    }
}

