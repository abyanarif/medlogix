<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineOutflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    /**
     * Show the form for editing the specified medicine.
     */
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified medicine in database.
     */
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

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

        // Standardize alert prefix on general info
        if (!str_starts_with($validated['informasi_general'], 'Alert:')) {
            $validated['informasi_general'] = 'Alert: ' . $validated['informasi_general'];
        }

        $medicine->update($validated);

        return redirect()->route('stock-reminder')->with('success', 'Data obat "' . $medicine->nama_obat . '" berhasil diperbarui!');
    }

    /**
     * Show the form for dispensing the specified medicine.
     */
    public function showDispenseForm($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('medicines.dispense', compact('medicine'));
    }

    /**
     * Process medicine dispensing (outflow).
     */
    public function dispense(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'jumlah_keluar' => 'required|integer|min:1|max:' . $medicine->stok,
            'tanggal_keluar' => 'required|date',
        ], [
            'jumlah_keluar.max' => 'Jumlah keluar tidak boleh melebihi stok yang tersedia (' . $medicine->stok . ' pcs).',
        ]);

        $jumlahKeluar = (int) $request->input('jumlah_keluar');
        $tanggalKeluar = $request->input('tanggal_keluar');

        // CRITICAL BUSINESS LOGIC: Use a Database Transaction (DB::transaction)
        DB::transaction(function () use ($medicine, $jumlahKeluar, $tanggalKeluar) {
            // 1. Insert new record into medicine_outflows table
            MedicineOutflow::create([
                'medicine_id' => $medicine->id,
                'jumlah_keluar' => $jumlahKeluar,
                'tanggal_keluar' => $tanggalKeluar,
            ]);

            // 2. Simultaneously decrement() the stok column of parent Medicine by the jumlah_keluar amount
            $medicine->decrement('stok', $jumlahKeluar);
        });

        return redirect()->route('stock-reminder')->with('success', 'Pengeluaran obat "' . $medicine->nama_obat . '" sebanyak ' . $jumlahKeluar . ' pcs berhasil dicatat!');
    }
}
