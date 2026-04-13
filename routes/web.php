<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\MetodoPagoController;

// ─── HOME ────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

// ─── PERFIL (requiere auth) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── GOOGLE AUTH ─────────────────────────────────────────────────
Route::get('/auth/google', [SocialController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialController::class, 'callback'])->name('auth.google.callback');

// ─── TIENDA PÚBLICA ──────────────────────────────────────────────
Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');
Route::get('/tienda/{producto:slug}', [TiendaController::class, 'show'])->name('tienda.show');

// ─── PANEL ADMIN ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // ✅ Ruta específica ANTES del resource para evitar conflicto con {producto}
    Route::post('productos/reordenar', [ProductoController::class, 'reordenar'])
        ->name('productos.reordenar');

    Route::resource('productos', ProductoController::class);

    // Métodos de pago
    Route::get('metodos-pago', [MetodoPagoController::class, 'index'])
        ->name('metodos-pago.index');
    Route::put('metodos-pago/{metodoPago}', [MetodoPagoController::class, 'update'])
        ->name('metodos-pago.update');
    Route::post('metodos-pago/{metodoPago}/toggle', [MetodoPagoController::class, 'toggle'])
        ->name('metodos-pago.toggle');
});

require __DIR__.'/auth.php';