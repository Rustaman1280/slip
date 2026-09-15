<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login sesuai mockup UKK
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('slip-gaji.index');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi login pengguna
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $usernameInput = $request->input('username');
        $password = $request->input('password');

        // Cari pengguna berdasarkan username atau email
        $user = User::where('username', $usernameInput)
            ->orWhere('email', $usernameInput)
            ->first();

        // Verifikasi kecocokan user dan password hash
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('slip-gaji.index')
                ->with('success', 'Selamat datang, '.$user->nama_lengkap.'!');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username atau kata sandi yang Anda masukkan salah.');
    }

    /**
     * Proses keluar sistem (logout)
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
