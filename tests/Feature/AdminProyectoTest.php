<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProyectoTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_puede_crear_proyecto_de_portafolio_sin_campos_de_inversion()
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post(route('admin.proyectos.store'), [
            'titulo'    => 'Sistema de riego automatizado',
            'categoria' => 'iot',
            'estado'    => 'en_curso',
            'tipo'      => 'portafolio',
            'visible'   => '1',
        ]);
        dump('STATUS: ' . $response->status());
        if ($response->exception) {
            dump('EXC: ' . get_class($response->exception));
            dump('MSG: ' . $response->exception->getMessage());
            dump($response->exception->getTraceAsString());
        }
        dump(substr($response->getContent(), 0, 500));
        $response->assertRedirect(route('admin.proyectos.index'));

        $this->assertDatabaseHas('proyectos', [
            'titulo'           => 'Sistema de riego automatizado',
            'tipo'             => 'portafolio',
            'inversion_estado' => null,
        ]);
    }

    public function test_admin_puede_crear_proyecto_de_inversion_con_datos_financieros()
    {
        $admin = $this->crearAdmin();

        $this->actingAs($admin)->post(route('admin.proyectos.store'), [
            'titulo'                      => 'Planta solar Trujillo',
            'categoria'                   => 'iot',
            'estado'                      => 'en_curso',
            'tipo'                        => 'inversion',
            'inversion_monto_total'       => 120000,
            'inversion_monto_disponible'  => 68500,
            'inversion_porcentaje_financiado' => 43,
            'inversion_tiempo_estimado'   => '12',
            'inversion_roi'               => '30%',
            'inversion_estado'            => 'disponible',
            'visible'                     => '1',
        ])->assertRedirect(route('admin.proyectos.index'));

        $this->assertDatabaseHas('proyectos', [
            'titulo'            => 'Planta solar Trujillo',
            'tipo'              => 'inversion',
            'inversion_estado'  => 'disponible',
            'inversion_roi'     => '30%',
        ]);
    }
}
