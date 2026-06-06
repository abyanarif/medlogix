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
        // Scope inflows (medicines coming IN) to the authenticated user, paginated (15 per page)
        $inflows = Medicine::where('user_id', auth()->id())
            ->orderBy('tanggal_masuk', 'desc')
            ->paginate(15, ['*'], 'inflows_page');

        // Scope outflows (medicines going OUT) to the authenticated user, eager loading relations, paginated (15 per page)
        $outflows = MedicineOutflow::whereHas('medicine', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('medicine')
            ->orderBy('tanggal_keluar', 'desc')
            ->paginate(15, ['*'], 'outflows_page');

        return view('logs.index', compact('inflows', 'outflows'));
    }
}
