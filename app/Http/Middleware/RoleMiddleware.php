<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * role:dashboard -> admin atau penjual yang sudah di-approve
     * role:admin     -> hanya admin
     */
    public function handle(Request $request, Closure $next, string $level = 'dashboard')
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowed = $level === 'admin' ? $user->isAdmin() : $user->canAccessDashboard();

        if (!$allowed) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }

            return redirect('/')->with('error', 'Kamu tidak punya akses ke halaman itu.');
        }

        return $next($request);
    }
}
