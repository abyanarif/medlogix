<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'username', 'email', 'phone', 'sipa', 'apotek_address', 'password',
    'payment_receipt', 'payment_status', 'subscription_ends_at', 'subscription_plan',
    'max_slots', 'yearly_bonus_claimed', 'pending_plan', 'pending_addon_qty'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_ends_at' => 'date',
            'yearly_bonus_claimed' => 'boolean',
            'max_slots' => 'integer',
            'pending_addon_qty' => 'integer',
        ];
    }

    /**
     * Get the notification settings for the user.
     */
    public function notification(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Notification::class);
    }

    /**
     * Get the medicines for the user.
     */
    public function medicines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}

