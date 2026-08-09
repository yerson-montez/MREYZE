<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_formulario_de_creacion_muestra_campos_de_pdf()
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->get(route('admin.proyectos.create'));
        $response->assertOk();
        $response->assertSee('name="contrato_pdf"', false);
        $response->assertSee('name="detalle_pdf"', false);
    }

    public function test_admin_puede_crear_proyecto_con_contrato_y_detalle_pdf()
    {
        Storage::fake('public');
        $admin = $this->crearAdmin();

        $this->actingAs($admin)->post(route('admin.proyectos.store'), [
            'titulo'          => 'Proyecto con documentos',
            'categoria'       => 'iot',
            'estado'          => 'en_curso',
            'tipo'            => 'inversion',
            'inversion_estado' => 'disponible',
            'visible'         => '1',
            'contrato_pdf'    => UploadedFile::fake()->create('contrato.pdf', 500, 'application/pdf'),
            'detalle_pdf'     => UploadedFile::fake()->create('detalle.pdf', 500, 'application/pdf'),
        ])->assertRedirect(route('admin.proyectos.index'));

        $proyecto = Proyecto::where('titulo', 'Proyecto con documentos')->firstOrFail();
        $this->assertNotNull($proyecto->contrato_pdf);
        $this->assertNotNull($proyecto->detalle_pdf);
        Storage::disk('public')->assertExists($proyecto->contrato_pdf);
        Storage::disk('public')->assertExists($proyecto->detalle_pdf);
    }
}
