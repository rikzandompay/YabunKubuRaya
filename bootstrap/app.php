<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // SECURITY: Trust reverse proxy (Cloudflare/CDN/Tunnel) untuk mendeteksi HTTPS & client IP dengan benar.
        $trustedProxies = env('TRUSTED_PROXIES', '*');
        if ($trustedProxies) {
            $middleware->trustProxies(at: $trustedProxies === '*' ? '*' : explode(',', $trustedProxies));
        }

        // SECURITY: Alias middleware untuk role-based access control
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        // SECURITY: Attach HTTP security headers to all web requests
        $middleware->web(append: [
            SecurityHeadersMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
