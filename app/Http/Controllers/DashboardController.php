<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Apoteker: Overview branch.
     * Displays a summary list: Nama Obat, Jumlah obat keluar/pembelian, Informasi general, Harga obat.
     */
    public function index()
    {
        $medicines = Medicine::all()->map(function ($medicine) {
            // Dynamically calculate the total volume of medicine dispensed
            // Use Eloquent to query the MedicineOutflow model and SUM the jumlah_keluar for each specific medicine.
            $medicine->obat_keluar = \App\Models\MedicineOutflow::where('medicine_id', $medicine->id)->sum('jumlah_keluar');
            return $medicine;
        });

        // Retrieve user notification settings for header alert counts
        $userNotification = Auth::user()->notification ?? new Notification();

        // Calculate count of drugs requiring restocking
        $alertCount = 0;
        foreach ($medicines as $m) {
            $isLowStock = $m->stok <= ($userNotification->batas_minimal_stok ?? 10);
            $isNearExpiry = $m->remaining_days <= ($userNotification->waktu_restock_hari ?? 7);
            $isExpired = $m->remaining_days <= 0;

            if ($isExpired || $isNearExpiry || $isLowStock) {
                $alertCount++;
            }
        }

        return view('welcome', compact('medicines', 'userNotification', 'alertCount'));
    }

    /**
     * Kelola Inventory: Management branch.
     * Renders drug insertion and notification configuration forms.
     */
    public function inventory()
    {
        $userNotification = Auth::user()->notification ?? Notification::firstOrCreate([
            'user_id' => Auth::id()
        ], [
            'batas_minimal_stok' => 10,
            'waktu_restock_hari' => 7,
            'is_active' => true
        ]);

        return view('inventory', compact('userNotification'));
    }

    /**
     * Handle inputting new medicine records.
     */
    public function storeMedicine(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'no_batch' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'exp_date' => 'required|date',
            'harga' => 'required|integer|min:0',
            'informasi_general' => 'required|string',
            'alert_level' => 'required|in:danger,warning,info',
        ]);

        // Add visual prefix to make general info uniform
        if (!str_starts_with($validated['informasi_general'], 'Alert:')) {
            $validated['informasi_general'] = 'Alert: ' . $validated['informasi_general'];
        }

        Medicine::create($validated);

        return redirect()->route('inventory')->with('success', 'Obat baru berhasil ditambahkan ke inventory!');
    }

    /**
     * Handle update notification configuration.
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'batas_minimal_stok' => 'required|integer|min:0',
            'waktu_restock_hari' => 'required|integer|min:0',
            'is_active' => 'nullable|string', // Checkbox ON/OFF
        ]);

        $notification = Auth::user()->notification;

        if (!$notification) {
            $notification = new Notification();
            $notification->user_id = Auth::id();
        }

        $notification->batas_minimal_stok = $validated['batas_minimal_stok'];
        $notification->waktu_restock_hari = $validated['waktu_restock_hari'];
        $notification->is_active = isset($validated['is_active']) && $validated['is_active'] === 'on';
        $notification->save();

        return redirect()->route('inventory')->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }

    /**
     * Data Stock & Reminder: Summary branch.
     * Displays a data table containing Nama obat, Tanggal masuk, No. Batch, Jumlah stock, Exp Date, and dynamic Status.
     */
    public function stockReminder()
    {
        $medicines = Medicine::all();
        $userNotification = Auth::user()->notification ?? new Notification([
            'batas_minimal_stok' => 10,
            'waktu_restock_hari' => 7,
            'is_active' => true
        ]);

        foreach ($medicines as $m) {
            $remainingDays = $m->remaining_days;

            if ($remainingDays <= 0) {
                $m->status = 'Kadaluarsa';
                $m->status_color = 'red';
            } elseif ($userNotification->is_active && ($remainingDays <= $userNotification->waktu_restock_hari || $m->stok <= $userNotification->batas_minimal_stok)) {
                $m->status = 'Restock Segera';
                $m->status_color = 'yellow';
            } else {
                $m->status = 'Aman';
                $m->status_color = 'green';
            }
        }

        return view('stock-reminder', compact('medicines', 'userNotification'));
    }
}
