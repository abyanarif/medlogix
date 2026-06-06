<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Setting;

class BillingController extends Controller
{
    /**
     * Display the billing index page for pharmacists.
     */
    public function index()
    {
        $user = auth()->user();
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('billing.index', compact('user', 'settings'));
    }

    /**
     * Handle the proof of payment upload.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pending_plan' => 'nullable|string|in:monthly,yearly',
            'pending_addon_qty' => 'nullable|integer|min:0',
        ]);

        $user = auth()->user();

        // Delete previous receipt file if exists to save disk space
        if ($user->payment_receipt) {
            $oldPath = str_replace('storage/', '', $user->payment_receipt);
            Storage::disk('public')->delete($oldPath);
        }

        // Store new receipt in storage/app/public/receipts
        $path = $request->file('payment_receipt')->store('receipts', 'public');
        $user->payment_receipt = 'storage/' . $path;
        $user->payment_status = 'pending';
        $user->pending_plan = $request->input('pending_plan', 'monthly');
        $user->pending_addon_qty = (int) $request->input('pending_addon_qty', 0);
        $user->save();

        return redirect()->route('billing.index')->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi admin.');
    }
}

