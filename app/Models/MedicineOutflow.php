<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['medicine_id', 'jumlah_keluar', 'tanggal_keluar'])]
class MedicineOutflow extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    /**
     * Get the medicine that owns the outflow record.
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
