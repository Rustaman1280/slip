<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    /**
     * Memeriksa apakah pengguna sudah login sebelum mengakses rute sistem
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika pengguna belum terotentikasi, alihkan ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan masuk (login) terlebih dahulu untuk mengakses sistem.');
        }

        return $next($request);
    }
}
