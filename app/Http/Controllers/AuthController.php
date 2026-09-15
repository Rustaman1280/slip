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
    // buat nampilin halaman login
    public function showLogin(): View|RedirectResponse
    {
        // kalau udah login langsung lempar ke halaman riwayat
        if (Auth::check()) {
            return redirect()->route('slip-gaji.index');
        }

        return view('auth.login');
    }

    // proses cek login user
    public function login(Request $request): RedirectResponse
    {
        // cek input username sama password jangan sampai kosong
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $usernameInput = $request->input('username');
        $password = $request->input('password');

        // cari user di database lewat username atau email
        $user = User::where('username', $usernameInput)
            ->orWhere('email', $usernameInput)
            ->first();

        // cek apakah passwordnya cocok sama yang di hash
        if ($user && Hash::check($password, $user->password)) {
            // kalau cocok langsung login-in dan buat session baru
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('slip-gaji.index')
                ->with('success', 'Selamat datang, '.$user->nama_lengkap.'!');
        }

        // kalau gagal balik lagi sambil bawa pesan error
        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username atau kata sandi yang Anda masukkan salah.');
    }

    // proses logout buat keluar dari akun
    public function logout(Request $request): RedirectResponse
    {
        // hapus session auth
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
