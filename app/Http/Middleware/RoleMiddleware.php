<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Izinkan admin, penjual, dan pembeli untuk mengakses
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'penjual' || Auth::user()->role == 'pembeli') {
                return $next($request);
            } else {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Access denied'], 403);
                } else {
                    return redirect('/')->with('warning', 'Anda tidak memiliki akses yang diperlukan');
                }
            }
        } else {
            return redirect('/login')->with('warning', 'Silahkan Login');
        }
    }
}

