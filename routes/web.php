<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\MetodoPagoController;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\Admin\ColaboradorAdminController;

// ─── HOME ────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('sections.inicio');
})->name('inicio');


Route::get('/test-pedidos', function () {
    return view('pedidos.test');
});


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

//Route::get('/tienda', function () {
    //return view('sections.tiendacliente');
//})->name('tiendacliente');

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

 // ─── CREAR PEDIDO (desde botón "Comprar" en tienda) ─────────────────────────────
Route::get('/pedidos/crear/{producto:slug}', [PedidoController::class, 'create'])
            ->name('pedidos.create');

Route::post('/pedidos/crear/{producto:slug}', [PedidoController::class, 'store'])
            ->name('pedidos.store');

Route::get('/pedidos/{pedido}/confirmacion', [PedidoController::class, 'confirmacion'])
    ->name('pedidos.confirmacion');

// ─── TIENDA PÚBLICA ──────────────────────────────────────────────
Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');
Route::get('/tienda/{producto:slug}', [TiendaController::class, 'show'])->name('tienda.show');

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
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'es_admin'])   // <-- requiere login + rol admin
    ->group(function () {

        // Panel principal de productos
        Route::get('/productos', [AdminProductoController::class, 'index'])
            ->name('productos.index');

        // Crear
        Route::get('/productos/crear', [AdminProductoController::class, 'create'])
            ->name('productos.create');
        Route::post('/productos', [AdminProductoController::class, 'store'])
            ->name('productos.store');

        // Editar
        Route::get('/productos/{producto}/editar', [AdminProductoController::class, 'edit'])
            ->name('productos.edit');
        Route::put('/productos/{producto}', [AdminProductoController::class, 'update'])
            ->name('productos.update');

        // Eliminar
        Route::delete('/productos/{producto}', [AdminProductoController::class, 'destroy'])
            ->name('productos.destroy');

        // Acciones rápidas
        Route::patch('/productos/{producto}/toggle', [AdminProductoController::class, 'toggleActivo'])
            ->name('productos.toggle');
        Route::patch('/productos/{producto}/stock', [AdminProductoController::class, 'actualizarStock'])
            ->name('productos.stock');
        
        // ─── PEDIDOS ADMIN ─────────────────────────────
        Route::get('pedidos', [PedidoAdminController::class, 'index'])
            ->name('pedidos.index');

        Route::get('pedidos/{pedido}', [PedidoAdminController::class, 'show'])
            ->name('pedidos.show');

        Route::patch('pedidos/{pedido}/estado', [PedidoAdminController::class, 'actualizarEstado'])
            ->name('pedidos.estado');


        // ─── COLABORADORES ADMIN ───────────────────────
        Route::get('colaboradores', [ColaboradorAdminController::class, 'index'])
            ->name('colaboradores.index');

        Route::get('colaboradores/crear', [ColaboradorAdminController::class, 'create'])
            ->name('colaboradores.create');

        Route::post('colaboradores', [ColaboradorAdminController::class, 'store'])
            ->name('colaboradores.store');

        Route::get('colaboradores/{colaborador}', [ColaboradorAdminController::class, 'show'])
            ->name('colaboradores.show');

        Route::get('colaboradores/{colaborador}/editar', [ColaboradorAdminController::class, 'edit'])
            ->name('colaboradores.edit');

        Route::put('colaboradores/{colaborador}', [ColaboradorAdminController::class, 'update'])
            ->name('colaboradores.update');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

// ─── AUTH ─────────────────────────────────────────────────────────
require __DIR__.'/auth.php';