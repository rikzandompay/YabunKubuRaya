<?php

namespace App\Policies;

use App\Models\Donatur;
use App\Models\User;

/**
 * Policy untuk otorisasi manajemen donatur.
 * Hanya superadmin dan admin yang dapat mengelola data donor.
 */
class DonaturPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function update(User $user, Donatur $donatur): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function delete(User $user, Donatur $donatur): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }
}
