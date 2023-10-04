<?php

namespace App\Policies;

use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PelanggaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->peran === 'Admin' || $user->peran === 'Kepala Sekolah' || $user->peran === 'Guru';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->peran === 'Guru';
    }

}
