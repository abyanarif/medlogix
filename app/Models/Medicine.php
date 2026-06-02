<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_obat', 'brand', 'informasi_general', 'no_batch', 'exp_date', 'stok', 'harga', 'tanggal_masuk'])]
class Medicine extends Model
{
    use HasFactory;

    protected $casts = [
        'exp_date' => 'date',
        'tanggal_masuk' => 'date',
    ];

    /**
     * Calculate remaining days until expiry.
     */
    public function getRemainingDaysAttribute(): int
    {
        if (!$this->exp_date) return 0;
        return (int) \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($this->exp_date)->startOfDay(), false);
    }
}
