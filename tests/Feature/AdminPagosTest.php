<?php

namespace Tests\Feature;

use App\Models\Inversion;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagosTest extends TestCase
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

    private function crearPago(Pedido $pedido, User $user, string $estado = 'pendiente'): Pago
    {
        return Pago::create([
            'pedido_id'   => $pedido->id,
            'user_id'     => $user->id,
            'monto'       => 50,
            'metodo_pago' => 'yape',
            'estado_pago' => $estado,
        ]);
    }

    public function test_invitado_no_puede_eliminar_pago()
    {
        $this->delete(route('admin.pagos.destroy', 1))
            ->assertRedirect();
    }

    public function test_usuario_no_admin_recibe_403()
    {
        $cliente = User::factory()->create(['role' => 'cliente']);
        $producto = $this->crearProducto();
        $pedido = $this->crearPedido($cliente, $producto);
        $pago = $this->crearPago($pedido, $cliente);

        $this->actingAs($cliente)
            ->delete(route('admin.pagos.destroy', $pago))
            ->assertForbidden();
    }

    public function test_boton_eliminar_aparece_en_cada_pago_con_confirmacion()
    {
        $admin = $this->crearAdmin();
        $producto = $this->crearProducto();
        $pedido = $this->crearPedido($admin, $producto);
        $this->crearPago($pedido, $admin);
        $this->crearPago($pedido, $admin);

        $response = $this->actingAs($admin)
            ->get(route('admin.pagos.index'));

        $response->assertOk()
            ->assertSee('Eliminar', false)
            ->assertSee('¿Eliminar este pago? Esta acción no se puede deshacer.', false);

        $this->assertSame(2, substr_count($response->getContent(), '¿Eliminar este pago? Esta acción no se puede deshacer.'));
    }

    public function test_boton_no_aparece_cuando_no_hay_pagos()
    {
        $admin = $this->crearAdmin();

        $this->actingAs($admin)
            ->get(route('admin.pagos.index'))
            ->assertOk()
            ->assertDontSee('Eliminar', false);
    }

    public function test_admin_elimina_solo_el_pago_seleccionado()
    {
        $admin = $this->crearAdmin();
        $producto = $this->crearProducto();
        $pedido = $this->crearPedido($admin, $producto);

        $pago1 = $this->crearPago($pedido, $admin, 'aprobado');
        $pago2 = $this->crearPago($pedido, $admin, 'pendiente');

        $this->actingAs($admin)
            ->delete(route('admin.pagos.destroy', $pago1))
            ->assertRedirect(route('admin.pagos.index'))
            ->assertSessionHas('success', 'Pago eliminado correctamente.');

        $this->assertDatabaseMissing('pagos', ['id' => $pago1->id]);
        $this->assertDatabaseHas('pagos', ['id' => $pago2->id]);
        $this->assertDatabaseCount('pagos', 1);
        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id]);
    }

    public function test_no_se_puede_eliminar_pago_vinculado_a_inversion()
    {
        $admin = $this->crearAdmin();

        $proyecto = Proyecto::create([
            'titulo'                     => 'Planta Solar Trujillo',
            'slug'                       => 'planta-solar-trujillo',
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

        $metodo = MetodoPago::create([
            'clave'  => 'yape',
            'nombre' => 'Yape',
            'emoji'  => '📱',
            'activo' => true,
            'datos'  => ['numero' => '999888777', 'titular' => 'MREYZE'],
            'orden'  => 1,
        ]);

        $inversion = Inversion::create([
            'user_id'        => $admin->id,
            'proyecto_id'    => $proyecto->id,
            'nombre'         => 'Inversionista',
            'email'          => 'inv@test.com',
            'monto'          => 500,
            'metodo_pago_id' => $metodo->id,
            'estado'         => 'pendiente',
        ]);

        $pago = Pago::create([
            'inversion_id' => $inversion->id,
            'user_id'      => $admin->id,
            'monto'        => 500,
            'metodo_pago'  => 'yape',
            'estado_pago'  => 'pendiente',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.pagos.destroy', $pago))
            ->assertNotFound();

        $this->assertDatabaseHas('pagos', ['id' => $pago->id]);
    }
}
