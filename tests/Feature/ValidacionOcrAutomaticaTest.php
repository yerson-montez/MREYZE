<?php

namespace Tests\Feature;

use App\DTOs\ResultadoOcr;
use App\Models\Inversion;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proyecto;
use App\Models\User;
use App\Services\TesseractOcrService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidacionOcrAutomaticaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    private function mockOcr(float $monto, string $codigo): void
    {
        $this->mock(TesseractOcrService::class, function ($mock) use ($monto, $codigo) {
            $mock->shouldReceive('extraerTexto')->andReturn(
                new ResultadoOcr(
                    codigoOperacion: $codigo,
                    monto:           $monto,
                    fecha:           '01/08/2026',
                    receptor:        'MREYZE',
                    confianzaGlobal: 0.95,
                    textoRaw:        "YAPE\nPAGO RECIBIDO\nS/ {$monto}\nNro. de operación: {$codigo}",
                    hashSha256:      hash('sha256', $codigo . $monto),
                )
            );
        });
    }

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function crearCliente(): User
    {
        return User::factory()->create();
    }

    private function crearProducto(float $precio): Producto
    {
        return Producto::create([
            'nombre'      => 'Kit IoT',
            'descripcion' => 'Descripción del producto',
            'precio'      => $precio,
            'slug'        => 'kit-iot-' . uniqid(),
            'stock'       => 10,
            'categoria'   => 'iot',
        ]);
    }

    private function crearPedido(User $cliente, Producto $producto): Pedido
    {
        $adelanto = round($producto->precio * 0.50, 2);

        return Pedido::create([
            'producto_id'       => $producto->id,
            'user_id'           => $cliente->id,
            'cliente_nombre'    => 'Cliente Test',
            'cliente_telefono'  => '999888777',
            'cliente_email'     => $cliente->email,
            'cliente_direccion' => 'Lima, Lima, Lima — Av. Test 123',
            'total'             => $producto->precio,
            'adelanto'          => $adelanto,
            'saldo'             => round($producto->precio - $adelanto, 2),
            'estado'            => 'pendiente',
        ]);
    }

    private function crearProyecto(): Proyecto
    {
        return Proyecto::create([
            'titulo'                     => 'Planta Solar Trujillo',
            'slug'                       => 'planta-solar-' . uniqid(),
            'descripcion_corta'          => 'Proyecto de inversión de prueba',
            'descripcion_completa'       => 'Descripción completa del proyecto.',
            'categoria'                  => 'iot',
            'estado'                     => 'en_curso',
            'tipo'                       => 'inversion',
            'visible'                    => true,
            'inversion_monto_total'      => 100000,
            'inversion_monto_disponible' => 60000,
            'inversion_porcentaje_financiado' => 40,
            'inversion_tiempo_estimado'  => '12',
            'inversion_roi'              => '30%',
            'inversion_estado'           => 'disponible',
        ]);
    }

    private function crearMetodo(): MetodoPago
    {
        return MetodoPago::create([
            'clave'  => 'yape',
            'nombre' => 'Yape',
            'activo' => true,
            'datos'  => ['numero' => '999888777', 'titular' => 'MREYZE'],
            'orden'  => 1,
        ]);
    }

    public function test_tienda_pago_adelanto_50_porciento_ocr_valida_automaticamente()
    {
        Storage::fake('public');
        $cliente  = $this->crearCliente();
        $producto = $this->crearProducto(100.00);
        $pedido   = $this->crearPedido($cliente, $producto);

        $this->mockOcr(50.00, '5504293');

        $this->actingAs($cliente)
            ->post(route('pedidos.comprobante', $pedido), [
                'numero_operacion' => '5504293',
                'comprobante'      => UploadedFile::fake()->image('comprobante.png', 600, 400),
            ])
            ->assertRedirect(route('pedidos.pago', $pedido));

        $pago = $pedido->pagos()->latest()->first();

        $this->assertNotNull($pago);
        $this->assertSame(50.00, (float) $pago->monto);
        $this->assertSame('validado', $pago->fresh()->ocr_resultado);
        $this->assertSame('aprobado', $pago->fresh()->estado_pago);

        $pedido->refresh();
        $this->assertSame('pago_confirmado', $pedido->estado);
        $this->assertSame(50.0, $pedido->total_pagado);
        $this->assertSame(50.0, max($pedido->total - $pedido->total_pagado, 0));
        $this->assertSame(1, $pedido->boletas()->count());
    }

    public function test_tienda_adelanto_50_ocr_con_monto_incorrecto_no_valida()
    {
        Storage::fake('public');
        $cliente  = $this->crearCliente();
        $producto = $this->crearProducto(100.00);
        $pedido   = $this->crearPedido($cliente, $producto);

        // El OCR lee S/ 100 (el total) pero el pago del adelanto espera S/ 50.
        $this->mockOcr(100.00, '5504293');

        $this->actingAs($cliente)
            ->post(route('pedidos.comprobante', $pedido), [
                'numero_operacion' => '5504293',
                'comprobante'      => UploadedFile::fake()->image('comprobante.png', 600, 400),
            ])
            ->assertRedirect(route('pedidos.pago', $pedido));

        $pago = $pedido->pagos()->latest()->first();

        $this->assertNotNull($pago);
        $this->assertSame('rechazado', $pago->fresh()->ocr_resultado);
        $this->assertSame('rechazado', $pago->fresh()->estado_pago);

        $pedido->refresh();
        $this->assertSame('pendiente', $pedido->estado);
        $this->assertSame(0.0, $pedido->total_pagado);
        $this->assertSame(0, $pedido->boletas()->count());
    }

    public function test_inversion_pago_100_porciento_ocr_valida_automaticamente()
    {
        Storage::fake('public');
        $user     = $this->crearCliente();
        $proyecto = $this->crearProyecto();
        $metodo   = $this->crearMetodo();

        $this->mockOcr(5000.00, 'OP-777');

        $this->actingAs($user)
            ->post(route('inversiones.store', $proyecto), [
                'nombre'           => 'María Pérez',
                'email'            => 'maria@test.com',
                'banco'            => 'BBVA',
                'cuenta_bancaria'  => '0011-0146-0100050182',
                'cci_bancario'     => '011-146-000100050182-83',
                'titular_cuenta'   => 'María Pérez',
                'monto'            => 5000,
                'metodo_pago_id'   => $metodo->id,
                'numero_operacion' => 'OP-777',
                'comprobante'      => UploadedFile::fake()->image('comprobante.png', 600, 400),
            ])
            ->assertRedirect(route('inversiones.gracias', $proyecto));

        $inversion = Inversion::first();
        $this->assertNotNull($inversion);

        $pago = Pago::where('inversion_id', $inversion->id)->first();
        $this->assertNotNull($pago);
        $this->assertSame(5000.00, (float) $pago->monto);
        $this->assertSame('validado', $pago->fresh()->ocr_resultado);
        $this->assertSame('aprobado', $pago->fresh()->estado_pago);
    }

    public function test_inversion_ocr_con_monto_distinto_al_100_porciento_no_valida()
    {
        Storage::fake('public');
        $user     = $this->crearCliente();
        $proyecto = $this->crearProyecto();
        $metodo   = $this->crearMetodo();

        // El inversionista afirma invertir S/ 5000 pero el comprobante dice S/ 1000.
        $this->mockOcr(1000.00, 'OP-888');

        $this->actingAs($user)
            ->post(route('inversiones.store', $proyecto), [
                'nombre'           => 'María Pérez',
                'email'            => 'maria@test.com',
                'banco'            => 'BBVA',
                'cuenta_bancaria'  => '0011-0146-0100050182',
                'cci_bancario'     => '011-146-000100050182-83',
                'titular_cuenta'   => 'María Pérez',
                'monto'            => 5000,
                'metodo_pago_id'   => $metodo->id,
                'numero_operacion' => 'OP-888',
                'comprobante'      => UploadedFile::fake()->image('comprobante.png', 600, 400),
            ])
            ->assertRedirect(route('inversiones.gracias', $proyecto));

        $inversion = Inversion::first();
        $this->assertNotNull($inversion);

        $pago = Pago::where('inversion_id', $inversion->id)->first();
        $this->assertNotNull($pago);
        $this->assertSame('rechazado', $pago->fresh()->ocr_resultado);
        $this->assertSame('rechazado', $pago->fresh()->estado_pago);
    }
}
