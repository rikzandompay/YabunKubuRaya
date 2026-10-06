<?php

namespace App\Policies;

use App\Models\FinancialTransaction;
use App\Models\User;

/**
 * Policy untuk otorisasi transaksi keuangan.
 * Hanya superadmin dan admin yang dapat mengelola data finansial.
 */
class FinancialTransactionPolicy
{
    /**
     * Determine if user dapat melihat daftar transaksi.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    /**
     * Determine if user dapat membuat transaksi baru.
     */
    public function create(User $user): bool
    {
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }

    /**
     * Determine if user dapat menghapus transaksi.
     */
    public function delete(User $user, FinancialTransaction $transaction): bool
    {
        // Hanya superadmin dan admin yang boleh delete
        return in_array($user->peran, ['superadmin', 'admin'], true);
    }
}
