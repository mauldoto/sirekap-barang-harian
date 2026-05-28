<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionByRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String ...$roles): Response
    {

        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        return back()->withErrors(['Anda tidak memiliki izin untuk mengakses halaman dan fitur tersebut!!!.']);
    }
}
