<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\MetodoPagoController;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\Admin\ColaboradorAdminController;
use App\Http\Controllers\SolicitudColaboradorController;
use App\Http\Controllers\PaymentController;

// ─── HOME ────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('sections.inicio');
})->name('inicio');

Route::get('/test-pedidos', function () {
    return view('pedidos.test');
});

// ─── PÁGINAS ESTÁTICAS ───────────────────────────────────────────
Route::get('/nosotros',  fn() => view('sections.nosotros'))->name('nosotros');
Route::get('/servicios', fn() => view('sections.servicios'))->name('servicios');
Route::get('/proyectos', fn() => view('sections.proyectos'))->name('proyectos');
Route::get('/blog',      fn() => view('sections.blog'))->name('blog');
Route::get('/contacto',  fn() => view('sections.contacto'))->name('contacto');

// ─── SUBSERVICIOS ────────────────────────────────────────────────
Route::get('/servicios/automatizacion', fn() => view('sections.servicios.automatizacion'))->name('servicios.automatizacion');
Route::get('/servicios/iot',            fn() => view('sections.servicios.iot'))->name('servicios.iot');
Route::get('/servicios/desarrollo',     fn() => view('sections.servicios.desarrollo'))->name('servicios.desarrollo');
Route::get('/servicios/consultoria',    fn() => view('sections.servicios.consultoria'))->name('servicios.consultoria');

// ─── TIENDA PÚBLICA ──────────────────────────────────────────────
Route::get('/tienda',               [TiendaController::class, 'index'])->name('tienda.index');
Route::get('/tienda/{producto:slug}', [TiendaController::class, 'show'])->name('tienda.show');

// Página principal → redirige al checkout
//Route::get('/', function () {
    //return redirect()->route('checkout');
//});

// ── Checkout ──────────────────────────────────────────────────────────────
// GET /checkout  → muestra la vista del checkout
//Route::get('/checkout', [PaymentController::class, 'showCheckout'])
    //->name('checkout');

// POST /pagar → procesa el pago (recibe token desde JS)
//Route::post('/pagar', [PaymentController::class, 'procesarPago'])
    //->name('pago.procesar');

// ── Webhook de Culqi ──────────────────────────────────────────────────────
// IMPORTANTE: excluir del CSRF middleware (ver app/Http/Middleware/VerifyCsrfToken.php)
//Route::post('/webhook/culqi', [PaymentController::class, 'webhook'])
    //->name('webhook.culqi');

// ─── COLABORADORES PÚBLICAS ──────────────────────────────────────
Route::get('/unete',        [SolicitudColaboradorController::class, 'show'])->name('colaboradores.solicitud');
Route::post('/unete',       [SolicitudColaboradorController::class, 'store'])->name('colaboradores.solicitud.store');
Route::get('/unete/gracias',[SolicitudColaboradorController::class, 'gracias'])->name('colaboradores.solicitud.gracias');

// ─── GOOGLE AUTH ─────────────────────────────────────────────────
Route::get('/auth/google',          [SocialController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialController::class, 'callback'])->name('auth.google.callback');

// ─── RUTAS AUTENTICADAS (usuarios normales) ───────────────────────
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pedidos — crear
    Route::get('/pedidos/crear/{producto:slug}',  [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos/crear/{producto:slug}', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}/confirmacion',  [PedidoController::class, 'confirmacion'])->name('pedidos.confirmacion');

    // Pedidos — mis pedidos
    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos'])->name('pedidos.mis');

    // Pedidos — pagos  ← ESTAS SON LAS QUE FALTABAN AQUÍ
    Route::post('/pedidos/{pedido}/pagar',   [PedidoController::class, 'pagar'])->name('pedidos.pagar');
    Route::post('/pedidos/confirmar-yape',   [PedidoController::class, 'confirmarYape'])->name('pedidos.confirmar-yape');
    Route::post('/pedidos/pagar-culqi',      [PedidoController::class, 'pagarCulqi'])->name('pedidos.pagar-culqi');

    // Colaborador link
    Route::get('/mi-link', [SolicitudColaboradorController::class, 'miLink'])->name('colaborador.link');
});

// ─── PANEL ADMIN ─────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'es_admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Métodos de pago
        Route::get('/metodos-pago',                       [MetodoPagoController::class, 'index'])->name('metodos-pago.index');
        Route::put('/metodos-pago/{metodoPago}',          [MetodoPagoController::class, 'update'])->name('metodos-pago.update');
        Route::patch('/metodos-pago/{metodoPago}/toggle', [MetodoPagoController::class, 'toggle'])->name('metodos-pago.toggle');

        // Solicitudes colaboradores
        Route::get('/colaboradores/solicitudes',                          [SolicitudColaboradorController::class, 'index'])->name('colaboradores.solicitudes.index');
        Route::patch('/colaboradores/solicitudes/{solicitud}/aprobar',    [SolicitudColaboradorController::class, 'aprobar'])->name('colaboradores.solicitudes.aprobar');
        Route::patch('/colaboradores/solicitudes/{solicitud}/rechazar',   [SolicitudColaboradorController::class, 'rechazar'])->name('colaboradores.solicitudes.rechazar');

        // Productos
        Route::get('/productos',                      [AdminProductoController::class, 'index'])->name('productos.index');
        Route::get('/productos/crear',                [AdminProductoController::class, 'create'])->name('productos.create');
        Route::post('/productos',                     [AdminProductoController::class, 'store'])->name('productos.store');
        Route::get('/productos/{producto}/editar',    [AdminProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}',           [AdminProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}',        [AdminProductoController::class, 'destroy'])->name('productos.destroy');
        Route::patch('/productos/{producto}/toggle',  [AdminProductoController::class, 'toggleActivo'])->name('productos.toggle');
        Route::patch('/productos/{producto}/stock',   [AdminProductoController::class, 'actualizarStock'])->name('productos.stock');

        // Pedidos admin
        Route::get('pedidos',                         [PedidoAdminController::class, 'index'])->name('pedidos.index');
        Route::get('pedidos/{pedido}',                [PedidoAdminController::class, 'show'])->name('pedidos.show');
        Route::patch('pedidos/{pedido}/estado',       [PedidoAdminController::class, 'actualizarEstado'])->name('pedidos.estado');

        // Colaboradores admin
        Route::get('colaboradores',                          [ColaboradorAdminController::class, 'index'])->name('colaboradores.index');
        Route::get('colaboradores/crear',                    [ColaboradorAdminController::class, 'create'])->name('colaboradores.create');
        Route::post('colaboradores',                         [ColaboradorAdminController::class, 'store'])->name('colaboradores.store');
        Route::get('colaboradores/{colaborador}',            [ColaboradorAdminController::class, 'show'])->name('colaboradores.show');
        Route::get('colaboradores/{colaborador}/editar',     [ColaboradorAdminController::class, 'edit'])->name('colaboradores.edit');
        Route::put('colaboradores/{colaborador}',            [ColaboradorAdminController::class, 'update'])->name('colaboradores.update');
        Route::patch('colaboradores/{colaborador}/aprobar',  [ColaboradorAdminController::class, 'aprobar'])->name('colaboradores.aprobar');
        Route::patch('colaboradores/{colaborador}/rechazar', [ColaboradorAdminController::class, 'rechazar'])->name('colaboradores.rechazar');
    });

Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';