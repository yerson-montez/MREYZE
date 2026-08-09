<?php

namespace Tests\Feature;

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\PedidoEstado;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPedidosTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function crearProducto(): Producto
    {
        return Producto::create([
            'codigo'      => 'PROD-' . uniqid(),
            'nombre'      => 'Producto Test',
            'descripcion' => 'Descripción de prueba',
            'precio'      => 100,
            'stock'       => 10,
            'categoria'   => 'iot',
            'estado'      => 'disponible',
            'activo'      => true,
        ]);
    }

    private function crearPedido(User $user, Producto $producto): Pedido
    {
        return Pedido::create([
            'producto_id'      => $producto->id,
            'user_id'          => $user->id,
            'cliente_nombre'   => 'Cliente Prueba',
            'cliente_telefono' => '999888777',
            'cliente_email'    => 'cliente@test.com',
            'cliente_direccion'=> 'Av. Prueba 123',
            'total'            => 100,
            'adelanto'         => 50,
            'saldo'            => 50,
        ]);
    }

    public function test_invitado_no_puede_eliminar_pedido()
    {
        $this->delete(route('admin.pedidos.destroy', 1))
            ->assertRedirect();
    }

    public function test_usuario_no_admin_recibe_403()
    {
        $cliente = User::factory()->create(['role' => 'cliente']);
        $producto = $this->crearProducto();
        $pedido = $this->crearPedido($cliente, $producto);

        $this->actingAs($cliente)
            ->delete(route('admin.pedidos.destroy', $pedido))
            ->assertForbidden();
    }

    public function test_boton_eliminar_aparece_en_cada_pedido_con_confirmacion()
    {
        $admin = $this->crearAdmin();
        $producto = $this->crearProducto();
        $this->crearPedido($admin, $producto);
        $this->crearPedido($admin, $producto);

        $response = $this->actingAs($admin)
            ->get(route('admin.pedidos.index'));

        $response->assertOk()
            ->assertSee('Eliminar', false)
            ->assertSee('¿Eliminar este pedido? Esta acción no se puede deshacer.', false);

        $this->assertSame(2, substr_count($response->getContent(), '¿Eliminar este pedido? Esta acción no se puede deshacer.'));
    }

    public function test_boton_no_aparece_cuando_no_hay_pedidos()
    {
        $admin = $this->crearAdmin();

        $this->actingAs($admin)
            ->get(route('admin.pedidos.index'))
            ->assertOk()
            ->assertDontSee('Eliminar', false);
    }

    public function test_admin_elimina_solo_el_pedido_seleccionado()
    {
        $admin = $this->crearAdmin();
        $producto = $this->crearProducto();

        $pedido1 = $this->crearPedido($admin, $producto);
        Pago::create([
            'pedido_id'   => $pedido1->id,
            'user_id'     => $admin->id,
            'monto'       => 50,
            'metodo_pago' => 'yape',
            'estado_pago' => 'aprobado',
        ]);
        PedidoEstado::create([
            'pedido_id' => $pedido1->id,
            'estado'    => 'pendiente',
        ]);

        $pedido2 = $this->crearPedido($admin, $producto);

        $this->actingAs($admin)
            ->delete(route('admin.pedidos.destroy', $pedido1))
            ->assertRedirect(route('admin.pedidos.index'))
            ->assertSessionHas('success', 'Pedido eliminado correctamente.');

        $this->assertDatabaseMissing('pedidos', ['id' => $pedido1->id]);
        $this->assertDatabaseHas('pedidos', ['id' => $pedido2->id]);
        $this->assertDatabaseCount('pedidos', 1);
        $this->assertDatabaseMissing('pagos', ['pedido_id' => $pedido1->id]);
        $this->assertDatabaseMissing('pedido_estados', ['pedido_id' => $pedido1->id]);
    }
}
