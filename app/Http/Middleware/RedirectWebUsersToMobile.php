<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWebUsersToMobile
{
    /**
     * Handle an incoming request.
     * Middleware placeholder — saat ini tidak memblokir akses web user.
     * Bisa diaktifkan di masa depan untuk redirect ke aplikasi mobile.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
