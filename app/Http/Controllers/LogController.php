<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineOutflow;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display the audit trail and transaction logs.
     */
    public function index()
    {
        // Fetch all inflow entries (medicines coming IN), ordered by tanggal_masuk descending
        $inflows = Medicine::orderBy('tanggal_masuk', 'desc')->get();

        // Fetch all outflow entries (medicines going OUT), eagers loading relation, ordered by tanggal_keluar descending
        $outflows = MedicineOutflow::with('medicine')->orderBy('tanggal_keluar', 'desc')->get();

        return view('logs.index', compact('inflows', 'outflows'));
    }
}
