<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memastikan user memiliki role admin atau superadmin.
 * Proteksi tambahan untuk route admin setelah auth middleware.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        $allowedRoles = ['superadmin', 'admin'];
        $userRole = auth()->user()->peran;

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman administrasi. Role Anda: '.$userRole);
        }

        return $next($request);
    }
}
