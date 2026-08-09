<?php

namespace Tests\Feature;

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoImagen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductosTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function crearProducto(array $extra = []): Producto
    {
        return Producto::create(array_merge([
            'codigo'    => 'PROD-' . uniqid(),
            'nombre'    => 'Producto Test',
            'descripcion' => 'Descripción de prueba',
            'precio'    => 100,
            'stock'     => 10,
            'categoria' => 'iot',
            'estado'    => 'disponible',
            'activo'    => true,
        ], $extra));
    }

    private function crearPedido(Producto $producto, User $user): Pedido
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

    public function test_invitado_no_puede_eliminar_todos()
    {
        $this->post(route('admin.productos.eliminarTodos'))
            ->assertRedirect();
    }

    public function test_usuario_no_admin_recibe_403()
    {
        $cliente = User::factory()->create(['role' => 'cliente']);

        $this->actingAs($cliente)
            ->post(route('admin.productos.eliminarTodos'))
            ->assertForbidden();
    }

    public function test_boton_aparece_solo_en_mis_productos_y_no_en_otras_paginas()
    {
        $admin = $this->crearAdmin();
        $this->crearProducto();

        $this->actingAs($admin)
            ->get(route('admin.productos.index'))
            ->assertOk()
            ->assertSee('Eliminar todos los productos')
            ->assertSee('¿Estás seguro de que deseas eliminar todos los productos?');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Eliminar todos los productos');
    }

    public function test_boton_no_aparece_cuando_no_hay_productos()
    {
        $admin = $this->crearAdmin();

        $this->actingAs($admin)
            ->get(route('admin.productos.index'))
            ->assertOk()
            ->assertDontSee('Eliminar todos los productos');
    }

    public function test_eliminar_todos_borra_productos_imagenes_pedidos_y_pagos()
    {
        Storage::fake('public');
        $admin = $this->crearAdmin();

        $producto1 = $this->crearProducto([
            'icono_imagen' => 'productos/icono1.png',
        ]);
        ProductoImagen::create([
            'producto_id'  => $producto1->id,
            'ruta'         => 'productos/img1.jpg',
            'orden'        => 1,
            'es_principal' => true,
        ]);
        $pedido1 = $this->crearPedido($producto1, $admin);
        Pago::create([
            'pedido_id'  => $pedido1->id,
            'user_id'    => $admin->id,
            'monto'      => 50,
            'metodo_pago'=> 'yape',
            'estado_pago'=> 'aprobado',
        ]);

        $producto2 = $this->crearProducto([
            'nombre' => 'Segundo Producto',
        ]);
        ProductoImagen::create([
            'producto_id'  => $producto2->id,
            'ruta'         => 'productos/img2.jpg',
            'orden'        => 1,
            'es_principal' => true,
        ]);
        $this->crearPedido($producto2, $admin);

        $this->actingAs($admin)
            ->post(route('admin.productos.eliminarTodos'))
            ->assertRedirect(route('admin.productos.index'))
            ->assertSessionHas('exito', 'Se eliminaron 2 productos.');

        $this->assertDatabaseCount('productos', 0);
        $this->assertDatabaseCount('producto_imagenes', 0);
        $this->assertDatabaseCount('pedidos', 0);
        $this->assertDatabaseCount('pagos', 0);

        Storage::disk('public')->assertMissing('productos/icono1.png');
        Storage::disk('public')->assertMissing('productos/img1.jpg');
        Storage::disk('public')->assertMissing('productos/img2.jpg');
    }

    public function test_tienda_publica_y_home_siguen_funcionando()
    {
        $this->crearProducto();

        $this->get(route('tienda.index'))
            ->assertOk()
            ->assertDontSee('Eliminar todos los productos');

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Eliminar todos los productos');
    }
}
