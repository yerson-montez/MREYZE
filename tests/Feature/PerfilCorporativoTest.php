<?php

namespace Tests\Feature;

use App\Models\PerfilCorporativo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PerfilCorporativoTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_visitante_puede_ver_la_pagina_de_perfil_corporativo()
    {
        $this->get(route('perfil.corporativo'))
            ->assertOk()
            ->assertSee('Perfil Corporativo');
    }

    public function test_pagina_muestra_mensaje_cuando_aun_no_hay_perfil()
    {
        $this->get(route('perfil.corporativo'))
            ->assertOk()
            ->assertSee('estará disponible próximamente');
    }

    public function test_admin_sube_pdf_y_visitante_lo_ve()
    {
        Storage::fake('public');

        $this->actingAs($this->crearAdmin())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->create('perfil-mreyze.pdf', 300, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.perfil-corporativo.index'));

        $perfil = PerfilCorporativo::first();

        $this->assertNotNull($perfil);
        $this->assertTrue($perfil->esPdf());
        Storage::disk('public')->assertExists($perfil->ruta);

        $this->get(route('perfil.corporativo'))
            ->assertOk()
            ->assertSee('perfil-mreyze.pdf')
            ->assertSee($perfil->url());
    }

    public function test_admin_puede_reemplazar_el_perfil_existente()
    {
        Storage::fake('public');

        $this->actingAs($this->crearAdmin())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->create('v1.pdf', 300, 'application/pdf'),
            ]);

        $rutaAnterior = PerfilCorporativo::first()->ruta;

        $this->actingAs($this->crearAdmin())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->create('v2.pdf', 300, 'application/pdf'),
            ]);

        $perfil = PerfilCorporativo::first();

        $this->assertSame(1, PerfilCorporativo::count());
        $this->assertNotSame($rutaAnterior, $perfil->ruta);
        Storage::disk('public')->assertMissing($rutaAnterior);
    }

    public function test_usuario_no_admin_no_puede_subir_perfil()
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->create('perfil.pdf', 300, 'application/pdf'),
            ])
            ->assertForbidden();
    }

    public function test_admin_rechaza_archivo_no_permitido()
    {
        $this->actingAs($this->crearAdmin())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->create('malware.exe', 300, 'application/x-msdownload'),
            ])
            ->assertSessionHasErrors('archivo');

        $this->assertSame(0, PerfilCorporativo::count());
    }

    public function test_admin_rechaza_imagen_porque_solo_se_acepta_pdf()
    {
        $this->actingAs($this->crearAdmin())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->image('logo.png'),
            ])
            ->assertSessionHasErrors('archivo');

        $this->assertSame(0, PerfilCorporativo::count());
    }

    public function test_admin_rechaza_pdf_mayor_a_10_mb()
    {
        $this->actingAs($this->crearAdmin())
            ->post(route('admin.perfil-corporativo.store'), [
                'archivo' => UploadedFile::fake()->create('pesado.pdf', 10240 * 2, 'application/pdf'),
            ])
            ->assertSessionHasErrors('archivo');

        $this->assertSame(0, PerfilCorporativo::count());
    }
}
