<?php

namespace Tests\Feature;

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
}
