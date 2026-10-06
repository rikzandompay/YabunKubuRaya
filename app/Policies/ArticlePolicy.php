<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

/**
 * Policy untuk otorisasi artikel.
 * Admin hanya bisa delete artikel miliknya sendiri, kecuali superadmin.
 */
class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function update(User $user, Article $article): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    public function delete(User $user, Article $article): bool
    {
        // Superadmin dapat delete semua artikel
        if ($user->peran === 'superadmin') {
            return true;
        }

        // Admin hanya bisa delete artikel sendiri (jika ada author_id di future)
        // Saat ini Article tidak punya author_id, jadi admin bisa delete semua
        // TODO: Tambah migration untuk author_id di Article
        return $user->peran === 'admin';
    }
}
