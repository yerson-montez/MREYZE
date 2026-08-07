<?php

namespace Tests\Feature;

use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoPagoConfirmacionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function crearProducto(): Producto
    {
        return Producto::create([
            'nombre'      => 'Kit IoT',
            'descripcion' => 'Descripción del producto',
            'precio'      => 100.00,
            'slug'        => 'kit-iot-' . uniqid(),
            'stock'       => 10,
            'categoria'   => 'iot',
        ]);
    }

    private function crearPedido(User $cliente, Producto $producto): Pedido
    {
        return Pedido::create([
            'producto_id'      => $producto->id,
            'user_id'          => $cliente->id,
            'cliente_nombre'   => 'Cliente Test',
            'cliente_telefono' => '999888777',
            'cliente_email'    => $cliente->email,
            'cliente_direccion'=> 'Lima, Lima, Lima — Av. Test 123',
            'total'            => 100.00,
            'adelanto'         => 50.00,
            'saldo'            => 50.00,
            'estado'           => 'pendiente',
        ]);
    }

    private function crearMetodoPago(string $clave = 'bcp'): MetodoPago
    {
        return MetodoPago::create([
            'clave'   => $clave,
            'nombre'  => ucfirst($clave),
            'activo'  => true,
            'datos'   => ['numero' => '1234567890', 'titular' => 'Tienda Online S.A.C.'],
            'orden'   => 1,
        ]);
    }

    public function test_total_pagado_suma_solo_los_pagos_aprobados()
    {
        $cliente  = User::factory()->create();
        $pedido   = $this->crearPedido($cliente, $this->crearProducto());

        Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'aprobado']);
        Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'rechazado']);

        $this->assertSame(50.0, $pedido->fresh()->total_pagado);

        $cargado = Pedido::with('pagos')->find($pedido->id);
        $this->assertSame(50.0, $cargado->total_pagado);
    }

    public function test_confirmar_pago_por_selector_aprueba_pago_y_genera_boleta()
    {
        $admin    = $this->crearAdmin();
        $cliente  = User::factory()->create();
        $pedido   = $this->crearPedido($cliente, $this->crearProducto());
        $pago     = Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'pendiente']);

        $this->actingAs($admin)
            ->patch(route('admin.pedidos.estado', $pedido), ['estado' => 'pago_confirmado'])
            ->assertRedirect(route('admin.pedidos.show', $pedido));

        $this->assertSame('aprobado', $pago->fresh()->estado_pago);
        $this->assertNotNull($pago->fresh()->fecha_pago);
        $this->assertSame($admin->id, $pago->fresh()->aprobado_por);
        $this->assertDatabaseHas('boletas', ['pedido_id' => $pedido->id, 'total' => 50.00]);

        $pedido->refresh();
        $this->assertSame('pago_confirmado', $pedido->estado);
        $this->assertSame(50.0, $pedido->total_pagado);
        $this->assertSame(50.0, max($pedido->total - $pedido->total_pagado, 0));
    }

    public function test_reconfirmar_pago_no_genera_boleta_duplicada()
    {
        $admin    = $this->crearAdmin();
        $cliente  = User::factory()->create();
        $pedido   = $this->crearPedido($cliente, $this->crearProducto());
        $pago     = Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'pendiente']);

        $this->actingAs($admin)->patch(route('admin.pedidos.estado', $pedido), ['estado' => 'pago_confirmado']);
        $this->actingAs($admin)->patch(route('admin.pedidos.estado', $pedido), ['estado' => 'pago_confirmado']);

        $this->assertSame(1, $pedido->boletas()->count());
        $this->assertSame('aprobado', $pago->fresh()->estado_pago);
    }

    public function test_mis_pedidos_muestra_total_pagado_y_saldo_desde_bd()
    {
        $cliente  = User::factory()->create();
        $pedido   = $this->crearPedido($cliente, $this->crearProducto());
        Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'aprobado']);

        $response = $this->actingAs($cliente)->get(route('pedidos.mis'));

        $response->assertOk();
        $response->assertSee('Total pagado');
        $response->assertSee('Saldo');
    }

    public function test_cliente_puede_cancelar_solo_en_estados_iniciales()
    {
        $cliente  = User::factory()->create();
        $producto = $this->crearProducto();

        $pendiente = $this->crearPedido($cliente, $producto);

        $this->actingAs($cliente)
            ->get(route('pedidos.mis'))
            ->assertOk()
            ->assertSee('Cancelar pedido');

        $this->actingAs($cliente)
            ->post(route('pedidos.cancelar', $pendiente))
            ->assertRedirect(route('pedidos.mis'));

        $this->assertSame('cancelado', $pendiente->fresh()->estado);
        $this->assertDatabaseHas('pedido_estados', ['pedido_id' => $pendiente->id, 'estado' => 'cancelado']);

        $pagoConfirmado = $this->crearPedido($cliente, $producto);
        $pagoConfirmado->update(['estado' => 'pago_confirmado']);

        $this->actingAs($cliente)
            ->post(route('pedidos.cancelar', $pagoConfirmado))
            ->assertRedirect(route('pedidos.mis'));

        $this->assertSame('cancelado', $pagoConfirmado->fresh()->estado);
    }

    public function test_cliente_no_puede_cancelar_pedido_en_procesamiento()
    {
        $cliente = User::factory()->create();
        $pedido  = $this->crearPedido($cliente, $this->crearProducto());
        $pedido->update(['estado' => 'preparando']);

        $this->actingAs($cliente)
            ->post(route('pedidos.cancelar', $pedido))
            ->assertSessionHasErrors(['pedido' => 'Este pedido ya no puede ser cancelado.']);

        $this->assertSame('preparando', $pedido->fresh()->estado);
    }

    public function test_mis_pedidos_no_muestra_cancelar_en_estados_de_procesamiento()
    {
        $cliente = User::factory()->create();
        $pedido  = $this->crearPedido($cliente, $this->crearProducto());
        $pedido->update(['estado' => 'preparando']);

        $this->actingAs($cliente)
            ->get(route('pedidos.mis'))
            ->assertOk()
            ->assertDontSee('Cancelar pedido')
            ->assertDontSee('Completar pago pendiente');
    }

    public function test_pedido_show_muestra_cancelar_y_completar_pago_en_estado_inicial()
    {
        $cliente = User::factory()->create();
        $pedido  = $this->crearPedido($cliente, $this->crearProducto());
        Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'aprobado']);
        $pedido->update(['estado' => 'pago_confirmado']);

        $this->actingAs($cliente)
            ->get(route('pedidos.show', $pedido))
            ->assertOk()
            ->assertSee('Completar pago pendiente')
            ->assertSee('Cancelar pedido');
    }

    public function test_pago_saldo_crea_pago_pendiente_de_validacion_admin()
    {
        $cliente = User::factory()->create();
        $pedido  = $this->crearPedido($cliente, $this->crearProducto());
        $this->crearMetodoPago('bcp');

        Pago::create(['pedido_id' => $pedido->id, 'user_id' => $cliente->id, 'monto' => 50.00, 'estado_pago' => 'aprobado']);
        $pedido->update(['estado' => 'pago_confirmado']);

        $this->actingAs($cliente)
            ->post(route('pedidos.comprobante', $pedido), [
                'metodo_pago'       => 'bcp',
                'numero_operacion'  => '123456789',
            ])
            ->assertRedirect(route('pedidos.pago', $pedido));

        $pago = $pedido->pagos()->latest('id')->first();

        $this->assertNotNull($pago);
        $this->assertSame(50.00, (float) $pago->monto);
        $this->assertSame('bcp', $pago->metodo_pago);
        $this->assertSame('pendiente', $pago->estado_pago);
        $this->assertSame('pendiente', $pago->ocr_resultado);
        $this->assertSame('pago_confirmado', $pedido->fresh()->estado);
        $this->assertSame(50.0, $pedido->fresh()->total_pagado);
    }

    public function test_adelanto_sigue_creando_pago_con_metodo_yape()
    {
        $cliente = User::factory()->create();
        $pedido  = $this->crearPedido($cliente, $this->crearProducto());

        $this->actingAs($cliente)
            ->post(route('pedidos.comprobante', $pedido), [
                'numero_operacion' => '987654321',
            ])
            ->assertRedirect(route('pedidos.pago', $pedido));

        $pago = $pedido->pagos()->latest()->first();

        $this->assertNotNull($pago);
        $this->assertSame(50.00, (float) $pago->monto);
        $this->assertSame('yape', $pago->metodo_pago);
    }
}
