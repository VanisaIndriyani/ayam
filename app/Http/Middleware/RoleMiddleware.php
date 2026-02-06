<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek user belum login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek role via STRING, bukan via relasi role->name
        if ($request->user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
