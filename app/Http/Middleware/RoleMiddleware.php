<?php
// File: app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // TAMBAHAN INI

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) { // FIXED
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role; // FIXED

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access. Your role: ' . $userRole . ', Required roles: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
