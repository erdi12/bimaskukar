<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $action): Response
    {
        // Jika user tidak login, redirect ke login
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Definisikan permission berdasarkan action
        $permissions = [
            'create' => ['Admin', 'Editor', 'Operator'],
            'edit' => ['Admin', 'Editor'],
            'delete' => ['Admin'],
            'view' => ['Admin', 'Editor', 'Operator', 'Viewer'],
        ];

        // Cek apakah action tersebut memerlukan permission khusus
        if (isset($permissions[$action])) {
            $allowedRoles = $permissions[$action];
            
            // Cek apakah user memiliki role yang diizinkan
            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    return $next($request);
                }
            }

            // Jika user tidak memiliki permission, abort
            abort(403, 'Anda tidak memiliki permission untuk ' . $action . ' resource ini');
        }

        return $next($request);
    }
}
