<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    // middleware buat ngecek user udah login atau belum
    public function handle(Request $request, Closure $next): Response
    {
        // kalau belum login, tendang balik ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan masuk (login) terlebih dahulu untuk mengakses sistem.');
        }

        return $next($request);
    }
}
