<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user tidak login, redirect ke login
        if (!auth()->check()) {
            return redirect('login');
        }

        // Jika tidak ada roles yang diberikan, allow
        if (empty($roles)) {
            return $next($request);
        }

        // Cek apakah user memiliki salah satu dari roles yang diizinkan
        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role)) {
                return $next($request);
            }
        }

        // Jika user tidak memiliki role yang diizinkan, abort
        abort(403, 'Anda tidak memiliki akses untuk resource ini');
    }
}
