<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
