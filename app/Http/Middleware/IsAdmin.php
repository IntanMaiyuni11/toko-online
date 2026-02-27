<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next): Response
{
    // Jika tidak login, lempar ke login
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Ambil role, hilangkan spasi, dan jadikan Uppercase
    $role = strtoupper(trim(Auth::user()->roles));

    if ($role === 'ADMIN') {
        return $next($request);
    }

    // Jika user biasa mencoba masuk ke area admin
    return redirect()->route('dashboard')->with('error', 'Akses ditolak!');
}
}