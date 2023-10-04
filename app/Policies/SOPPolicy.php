<?php

namespace App\Policies;

use App\Models\SOP;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SOPPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->peran === 'Admin' || $user->peran === 'Guru';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->peran === 'Admin';
    }
}
