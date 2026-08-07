<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductoController;
use App\Http\Controllers\Admin\MetodoPagoController;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\Admin\PagoAdminController;
use App\Http\Controllers\Admin\BoletaController;
use App\Http\Controllers\Admin\ColaboradorAdminController;
use App\Http\Controllers\Admin\AdminProyectoController;
use App\Http\Controllers\Admin\AdminProyectoInversionController;
use App\Http\Controllers\Admin\AdminInversionController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ProyectoInversionController;
use App\Http\Controllers\InversionController;
use App\Http\Controllers\SolicitudColaboradorController;

// ─── HOME ────────────────────────────────────────────────────────
use App\Models\Producto;
use App\Models\MetodoPago;

Route::get('/', function () {

    $productosHome = Producto::where('activo', true)
        ->where('estado', 'disponible')
        ->take(8)
        ->get();

    $metodosPago = MetodoPago::where('activo', true)->get();

    return view('sections.inicio', compact(
        'productosHome',
        'metodosPago'
    ));

})->name('inicio');

// ─── PÁGINAS ESTÁTICAS ──────────────────────────────────────────
Route::get('/nosotros',  fn() => view('sections.nosotros'))->name('nosotros');
Route::get('/servicios', fn() => view('sections.servicios'))->name('servicios');
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos');
Route::get('/proyectos/{proyecto:slug}', [ProyectoController::class, 'show'])->name('proyectos.show');
Route::get('/proyectos-de-inversion', [ProyectoInversionController::class, 'index'])->name('proyectos.inversion');
Route::get('/proyectos/{proyecto:slug}/invertir', [InversionController::class, 'create'])->name('inversiones.create');
Route::post('/proyectos/{proyecto:slug}/invertir', [InversionController::class, 'store'])->name('inversiones.store');
Route::get('/proyectos/{proyecto:slug}/invertir/gracias', [InversionController::class, 'gracias'])->name('inversiones.gracias');
Route::get('/blog',      fn() => view('sections.blog'))->name('blog');
Route::get('/contacto',  fn() => view('sections.contacto'))->name('contacto');

// ─── SUBSERVICIOS ────────────────────────────────────────────────
Route::get('/servicios/automatizacion', fn() => view('sections.servicios.automatizacion'))->name('servicios.automatizacion');
Route::get('/servicios/iot',            fn() => view('sections.servicios.iot'))->name('servicios.iot');
Route::get('/servicios/desarrollo',     fn() => view('sections.servicios.desarrollo'))->name('servicios.desarrollo');
Route::get('/servicios/consultoria',    fn() => view('sections.servicios.consultoria'))->name('servicios.consultoria');

// ─── TIENDA PÚBLICA ──────────────────────────────────────────────
Route::get('/tienda',                 [TiendaController::class, 'index'])->name('tienda.index');
Route::get('/tienda/{producto:slug}', [TiendaController::class, 'show'])->name('tienda.show');

// ─── COLABORADORES PÚBLICAS ──────────────────────────────────────
Route::get('/unete',        [SolicitudColaboradorController::class, 'show'])->name('colaboradores.solicitud');
Route::post('/unete',       [SolicitudColaboradorController::class, 'store'])->name('colaboradores.solicitud.store');
Route::get('/unete/gracias',[SolicitudColaboradorController::class, 'gracias'])->name('colaboradores.solicitud.gracias');

// ─── GOOGLE AUTH ─────────────────────────────────────────────────
Route::get('/auth/google',          [SocialController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialController::class, 'callback'])->name('auth.google.callback');

// ─── RUTAS AUTENTICADAS (usuarios normales) ───────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pedidos/crear/{producto:slug}',  [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos/crear/{producto:slug}', [PedidoController::class, 'store'])->name('pedidos.store');

    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos'])->name('pedidos.mis');
    Route::get('/mis-pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/mis-pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');

    Route::get('/mis-inversiones', [InversionController::class, 'misInversiones'])->name('inversiones.mis');
    Route::get('/mis-inversiones/{inversion}/contrato', [InversionController::class, 'descargarContrato'])->name('inversiones.contrato');

    // Pago con Yape
    Route::get('/pedido/{pedido}/pago',        [PagoController::class, 'show'])->name('pedidos.pago');
    Route::post('/pedido/{pedido}/comprobante', [PagoController::class, 'storeComprobante'])->name('pedidos.comprobante');
    Route::get('/pedido/{pedido}/estado',      [PagoController::class, 'estado'])->name('pedidos.estado');
    Route::get('/pedido/{pedido}/boleta',      [PagoController::class, 'showBoleta'])->name('pedidos.boleta');
    Route::get('/pedido/{pedido}/boleta.pdf',  [PagoController::class, 'boletaPdf'])->name('pedidos.boleta.pdf');

    Route::get('/mi-link', [SolicitudColaboradorController::class, 'miLink'])->name('colaborador.link');
});

// ─── PANEL ADMIN ─────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'es_admin'])
    ->group(function () {

        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/metodos-pago',                       [MetodoPagoController::class, 'index'])->name('metodos-pago.index');
        Route::put('/metodos-pago/{metodoPago}',          [MetodoPagoController::class, 'update'])->name('metodos-pago.update');
        Route::patch('/metodos-pago/{metodoPago}/toggle', [MetodoPagoController::class, 'toggle'])->name('metodos-pago.toggle');

        Route::get('/colaboradores/solicitudes',                          [SolicitudColaboradorController::class, 'index'])->name('colaboradores.solicitudes.index');
        Route::patch('/colaboradores/solicitudes/{solicitud}/aprobar',    [SolicitudColaboradorController::class, 'aprobar'])->name('colaboradores.solicitudes.aprobar');
        Route::patch('/colaboradores/solicitudes/{solicitud}/rechazar',   [SolicitudColaboradorController::class, 'rechazar'])->name('colaboradores.solicitudes.rechazar');

Route::get('/productos',                      [AdminProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/crear',                [AdminProductoController::class, 'create'])->name('productos.create');
Route::post('/productos',                     [AdminProductoController::class, 'store'])->name('productos.store');

Route::get('/productos/importar',
    function () {
        return view('admin.productos.importar');
    }
)->name('productos.importar.form');


Route::post('/productos/importar',
    [AdminProductoController::class, 'importar']
)->name('productos.importar');
        Route::get('/productos/{producto}/editar',    [AdminProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}',           [AdminProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}',        [AdminProductoController::class, 'destroy'])->name('productos.destroy');
        Route::patch('/productos/{producto}/toggle',  [AdminProductoController::class, 'toggleActivo'])->name('productos.toggle');
        Route::patch('/productos/{producto}/stock',   [AdminProductoController::class, 'actualizarStock'])->name('productos.stock');

        Route::patch('/productos/imagenes/{imagen}/principal', [AdminProductoController::class, 'setPrincipal'])->name('productos.imagenes.principal');
        Route::delete('/productos/imagenes/{imagen}',         [AdminProductoController::class, 'eliminarImagen'])->name('productos.imagenes.destroy');

        Route::get('pedidos',                    [PedidoAdminController::class, 'index'])->name('pedidos.index');
        Route::get('pedidos/{pedido}',           [PedidoAdminController::class, 'show'])->name('pedidos.show');
        Route::patch('pedidos/{pedido}/estado',  [PedidoAdminController::class, 'actualizarEstado'])->name('pedidos.estado');

        // Pagos admin
        Route::get('pagos',                    [PagoAdminController::class, 'index'])->name('pagos.index');
        Route::get('pagos/{pago}',             [PagoAdminController::class, 'show'])->name('pagos.show');
        Route::post('pagos/{pago}/aprobar',    [PagoAdminController::class, 'aprobar'])->name('pagos.aprobar');

        // Boletas admin
        Route::get('boletas/{boleta}',         [BoletaController::class, 'show'])->name('boletas.show');
        Route::get('boletas/{boleta}/pdf',     [BoletaController::class, 'pdf'])->name('boletas.pdf');

        Route::get('colaboradores',                          [ColaboradorAdminController::class, 'index'])->name('colaboradores.index');
        Route::get('colaboradores/crear',                    [ColaboradorAdminController::class, 'create'])->name('colaboradores.create');
        Route::post('colaboradores',                         [ColaboradorAdminController::class, 'store'])->name('colaboradores.store');
        Route::get('colaboradores/{colaborador}',            [ColaboradorAdminController::class, 'show'])->name('colaboradores.show');
        Route::get('colaboradores/{colaborador}/editar',     [ColaboradorAdminController::class, 'edit'])->name('colaboradores.edit');
        Route::put('colaboradores/{colaborador}',            [ColaboradorAdminController::class, 'update'])->name('colaboradores.update');
        Route::patch('colaboradores/{colaborador}/aprobar',  [ColaboradorAdminController::class, 'aprobar'])->name('colaboradores.aprobar');
        Route::patch('colaboradores/{colaborador}/rechazar', [ColaboradorAdminController::class, 'rechazar'])->name('colaboradores.rechazar');

        Route::get('/proyectos',                      [AdminProyectoController::class, 'index'])->name('proyectos.index');
        Route::get('/proyectos/crear',                [AdminProyectoController::class, 'create'])->name('proyectos.create');
        Route::post('/proyectos',                     [AdminProyectoController::class, 'store'])->name('proyectos.store');
        Route::get('/proyectos/{proyecto}/editar',    [AdminProyectoController::class, 'edit'])->name('proyectos.edit');
        Route::put('/proyectos/{proyecto}',           [AdminProyectoController::class, 'update'])->name('proyectos.update');
        Route::delete('/proyectos/{proyecto}',        [AdminProyectoController::class, 'destroy'])->name('proyectos.destroy');
        Route::patch('/proyectos/{proyecto}/toggle',  [AdminProyectoController::class, 'toggleVisibilidad'])->name('proyectos.toggle');
        Route::patch('/proyectos/imagenes/{imagen}/principal', [AdminProyectoController::class, 'setPrincipal'])->name('proyectos.imagenes.principal');
        Route::delete('/proyectos/imagenes/{imagen}',         [AdminProyectoController::class, 'eliminarImagen'])->name('proyectos.imagenes.destroy');

        Route::post('/proyectos/{proyecto}/contrato',       [AdminProyectoController::class, 'subirContrato'])->name('proyectos.contrato');
        Route::delete('/proyectos/{proyecto}/contrato',     [AdminProyectoController::class, 'eliminarContrato'])->name('proyectos.contrato.eliminar');

        Route::get('/proyectos-inversion',                             [AdminProyectoInversionController::class, 'index'])->name('proyectos-inversion.index');
        Route::get('/proyectos-inversion/crear',                       [AdminProyectoInversionController::class, 'create'])->name('proyectos-inversion.create');
        Route::post('/proyectos-inversion',                            [AdminProyectoInversionController::class, 'store'])->name('proyectos-inversion.store');
        Route::get('/proyectos-inversion/{proyectoInversion}/editar',  [AdminProyectoInversionController::class, 'edit'])->name('proyectos-inversion.edit');
        Route::put('/proyectos-inversion/{proyectoInversion}',         [AdminProyectoInversionController::class, 'update'])->name('proyectos-inversion.update');
        Route::delete('/proyectos-inversion/{proyectoInversion}',      [AdminProyectoInversionController::class, 'destroy'])->name('proyectos-inversion.destroy');
        Route::patch('/proyectos-inversion/{proyectoInversion}/toggle',[AdminProyectoInversionController::class, 'toggleVisibilidad'])->name('proyectos-inversion.toggle');

        Route::get('/inversiones',                          [AdminInversionController::class, 'index'])->name('inversiones.index');
        Route::get('/inversiones/{inversion}',              [AdminInversionController::class, 'show'])->name('inversiones.show');
        Route::post('/inversiones/{inversion}/aprobar',     [AdminInversionController::class, 'aprobar'])->name('inversiones.aprobar');
        Route::post('/inversiones/{inversion}/rechazar',    [AdminInversionController::class, 'rechazar'])->name('inversiones.rechazar');
        Route::get('/inversiones/{inversion}/comprobante',  [AdminInversionController::class, 'descargarComprobante'])->name('inversiones.comprobante');
        Route::post('/inversiones/{inversion}/contrato',    [AdminInversionController::class, 'subirContrato'])->name('inversiones.contrato');
        Route::get('/inversiones/{inversion}/contrato',     [AdminInversionController::class, 'descargarContrato'])->name('inversiones.contrato.descargar');
    });

Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
