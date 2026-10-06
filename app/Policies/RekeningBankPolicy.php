<?php

namespace App\Policies;

use App\Models\RekeningBank;
use App\Models\User;

class RekeningBankPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function update(User $user, RekeningBank $bank): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function delete(User $user, RekeningBank $bank): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }
}
