<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan baris ini
use Symfony\Component\HttpFoundation\Response;

class CheckSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function handle(Request $request, Closure $next): Response
{
    // Izinkan semua yang sudah login (ADMIN, USER, CUSTOMER) untuk akses chat
    if (Auth::check()) {
        return $next($request);
    }

    // Jika belum login sama sekali, baru arahkan ke home atau login
    return redirect('/');
}
}