<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    /**
     * Middleware untuk memeriksa role pengguna.
     * Mendukung multiple roles dengan pemisah pipe (|).
     *
     * Contoh penggunaan:
     *   - middleware('role:admin')
     *   - middleware('role:admin|opd')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if (! Auth::check()) {
            return redirect('/login');
        }

        $allowedRoles = explode('|', $role);

        if (! in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);

    }

}
