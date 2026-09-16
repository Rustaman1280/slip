<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SlipGajiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Slip Gaji UKK Junior Web Programmer
|--------------------------------------------------------------------------
*/

// kalau buka halaman awal langsung lempar ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// route buat login dan logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// route yang cuma bisa dibuka kalau udah login
Route::middleware(['cek.login'])->group(function () {
    Route::get('/slip-gaji', [SlipGajiController::class, 'index'])->name('slip-gaji.index');
    Route::get('/slip-gaji/tambah', [SlipGajiController::class, 'create'])->name('slip-gaji.create');
    Route::post('/slip-gaji', [SlipGajiController::class, 'store'])->name('slip-gaji.store');
    Route::get('/slip-gaji/{id}', [SlipGajiController::class, 'show'])->name('slip-gaji.show');
    Route::get('/slip-gaji/{id}/edit', [SlipGajiController::class, 'edit'])->name('slip-gaji.edit');
    Route::put('/slip-gaji/{id}', [SlipGajiController::class, 'update'])->name('slip-gaji.update');
    Route::get('/slip-gaji/{id}/cetak', [SlipGajiController::class, 'cetak'])->name('slip-gaji.cetak');
    Route::delete('/slip-gaji/{id}', [SlipGajiController::class, 'destroy'])->name('slip-gaji.destroy');
});
