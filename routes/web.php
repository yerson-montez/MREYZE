<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\MetodoPagoController;

// ─── HOME ────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('sections.inicio');
})->name('inicio');


// ─── PÁGINAS ESTÁTICAS (todas usan welcome como layout base) ─────
Route::get('/nosotros', function () {
    return view('sections.nosotros');
})->name('nosotros');

Route::get('/servicios', function () {
    return view('sections.servicios');
})->name('servicios');

Route::get('/proyectos', function () {
    return view('sections.proyectos');
})->name('proyectos');

Route::get('/tienda', function () {
    return view('sections.tiendacliente');
})->name('tiendacliente');

Route::get('/blog', function () {
    return view('sections.blog');
})->name('blog');

Route::get('/contacto', function () {
    return view('sections.contacto');
})->name('contacto');

// ─── SUBSERVICIOS ────────────────────────────────────────────────
Route::get('/servicios/automatizacion', function () {
    return view('sections.servicios.automatizacion');
})->name('servicios.automatizacion');

Route::get('/servicios/iot', function () {
    return view('sections.servicios.iot');
})->name('servicios.iot');

Route::get('/servicios/desarrollo', function () {
    return view('sections.servicios.desarrollo');
})->name('servicios.desarrollo');

Route::get('/servicios/consultoria', function () {
    return view('sections.servicios.consultoria');
})->name('servicios.consultoria');

// ─── TIENDA PÚBLICA ──────────────────────────────────────────────
//Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');
//Route::get('/tienda/{producto:slug}', [TiendaController::class, 'show'])->name('tienda.show');

// ─── GOOGLE AUTH ─────────────────────────────────────────────────
Route::get('/auth/google', [SocialController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialController::class, 'callback'])->name('auth.google.callback');

// ─── PERFIL (requiere auth) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── PANEL ADMIN ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::post('productos/reordenar', [ProductoController::class, 'reordenar'])
        ->name('productos.reordenar');

    Route::resource('productos', ProductoController::class);

    Route::get('metodos-pago', [MetodoPagoController::class, 'index'])
        ->name('metodos-pago.index');
    Route::put('metodos-pago/{metodoPago}', [MetodoPagoController::class, 'update'])
        ->name('metodos-pago.update');
    Route::post('metodos-pago/{metodoPago}/toggle', [MetodoPagoController::class, 'toggle'])
        ->name('metodos-pago.toggle');
});

// ─── AUTH ─────────────────────────────────────────────────────────
require __DIR__.'/auth.php';