<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'nama_obat', 'brand', 'informasi_general', 'alert_level', 'no_batch', 'exp_date', 'stok', 'harga', 'tanggal_masuk'])]
class Medicine extends Model
{
    use HasFactory;

    protected $casts = [
        'exp_date' => 'date',
        'tanggal_masuk' => 'date',
    ];

    /**
     * Get the user that owns the medicine.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the outflows for this medicine.
     */
    public function outflows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MedicineOutflow::class);
    }

    /**
     * Calculate remaining days until expiry.
     */
    public function getRemainingDaysAttribute(): int
    {
        if (!$this->exp_date) return 0;
        return (int) \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($this->exp_date)->startOfDay(), false);
    }
}
