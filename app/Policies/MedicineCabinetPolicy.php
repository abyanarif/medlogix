<?php

namespace App\Policies;

use App\Models\MedicineCabinet;
use App\Models\User;

class MedicineCabinetPolicy
{
    /**
     * Determine whether the user can delete the medicine cabinet entry.
     */
    public function delete(User $user, MedicineCabinet $cabinet): bool
    {
        return $user->id === $cabinet->user_id;
    }
}
