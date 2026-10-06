<?php

namespace App\Policies;

use App\Models\Katalog;
use App\Models\User;

class KatalogPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function update(User $user, Katalog $katalog): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function delete(User $user, Katalog $katalog): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }
}
