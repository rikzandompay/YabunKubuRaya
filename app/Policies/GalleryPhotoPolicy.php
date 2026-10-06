<?php

namespace App\Policies;

use App\Models\GalleryPhoto;
use App\Models\User;

/**
 * Policy untuk otorisasi galeri foto.
 */
class GalleryPhotoPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function update(User $user, GalleryPhoto $photo): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function delete(User $user, GalleryPhoto $photo): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }
}
