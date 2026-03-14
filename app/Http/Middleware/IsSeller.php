<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan baris ini
use Symfony\Component\HttpFoundation\Response;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengecek apakah user sudah login dan memiliki role USER atau ADMIN
        if (Auth::user() && (Auth::user()->roles == 'USER' || Auth::user()->roles == 'ADMIN')) {
            return $next($request);
        }

        // Jika dia CUSTOMER, lempar balik ke dashboard
        return redirect('/dashboard');
    }
}