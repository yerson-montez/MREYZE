<?php

namespace Tests\Feature;

use App\Models\Inversion;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class InversionTest extends TestCase
{
    use RefreshDatabase;

    private function crearProyecto(array $overrides = []): Proyecto
    {
        return Proyecto::create(array_merge([
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
            'whatsapp_numero'            => '51999888777',
            'whatsapp_mensaje'           => 'Hola',
        ], $overrides));
    }

    private function crearMetodo(): MetodoPago
    {
        return MetodoPago::create([
            'clave'  => 'yape',
            'nombre' => 'Yape',
            'emoji'  => '📱',
            'activo' => true,
            'datos'  => ['numero' => '999888777', 'titular' => 'MREYZE'],
            'orden'  => 1,
        ]);
    }

    private function crearAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_invitado_es_redirigido_a_login_al_entrar_a_invertir()
    {
        $proyecto = $this->crearProyecto();
        $this->crearMetodo();

        $this->get(route('inversiones.create', $proyecto))
            ->assertRedirect(route('auth.google'));
    }

    public function test_usuario_autenticado_ve_el_formulario_de_inversion_sin_simulacion()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $this->crearMetodo();

        $response = $this->actingAs($user)->get(route('inversiones.form', $proyecto));

        $response->assertOk();
        $response->assertSee($proyecto->titulo);
        $response->assertSee('Solicita tu inversión');
        $response->assertSee('Monto a invertir');
        $response->assertSee(route('inversiones.store', $proyecto), false);
        $response->assertDontSee('Simulador de rendimiento');
        $response->assertDontSee('Desliza para elegir cuánto quieres invertir');

        // Cambio 1: datos bancarios del inversionista
        $response->assertSee('Datos bancarios para recibir ganancias');
        $response->assertSee('Banco *');
        $response->assertSee('Número de cuenta *');
        $response->assertSee('CCI *');
        $response->assertSee('Titular de la cuenta *');

        // Cambio 2: solo la cuenta BBVA
        $response->assertSee('¿Cómo realizar tu pago?');
        $response->assertSee('BBVA');
        $response->assertSee('011-146-000100050182-83');
        $response->assertSee('0011-0146-0100050182');
        $response->assertSee('MReyze Automatización y Sistemas S.A.C.');

        // Cambio 3: QR oculto por defecto
        $response->assertSee('📱 Pagar con Yape / Plin');
        $response->assertSee('id="inv-qr-wrap"', false);
        $response->assertDontSee('inv-qr-wrap abierto', false);

        // Adjuntar comprobante y firma
        $response->assertSee('Adjuntar comprobante de pago');
        $response->assertSee('Adjuntar firma');
        $response->assertSee('name="comprobante"', false);
        $response->assertSee('name="firma"', false);
        $response->assertSee('accept="image/jpeg,image/png,image/webp"', false);

        // El bloque informativo del proyecto ya no aparece en el formulario
        $response->assertDontSee('Invierte en', false);
        $response->assertDontSee('Financiamiento del proyecto');
        $response->assertDontSee('Inversión total');
        $response->assertDontSee('Disponible aún');
        $response->assertDontSee('Tiempo de ejecución');
        $response->assertDontSee('Retorno estimado');
        $response->assertDontSee('Más oportunidades');
        $response->assertDontSee('Otros proyectos');
    }

    public function test_usuario_autenticado_ve_la_bienvenida_antes_del_formulario()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $this->crearMetodo();

        $response = $this->actingAs($user)->get(route('inversiones.create', $proyecto));

        $response->assertOk();

        // Solo la imagen de bienvenida y el botón continuar
        $response->assertSee('img/bienvenida-mreyze.svg', false);
        $response->assertSee('CONTINUAR CON LA INVERSIÓN');
        $response->assertSee(route('inversiones.form', $proyecto), false);

        // Sin formulario ni datos del proyecto
        $response->assertDontSee('Solicita tu inversión');
        $response->assertDontSee('Monto a invertir');
        $response->assertDontSee('Datos bancarios para recibir ganancias');
        $response->assertDontSee('Adjuntar firma');
        $response->assertDontSee('Invierte en', false);
        $response->assertDontSee('Financiamiento del proyecto');
        $response->assertDontSee('Simulador de rendimiento');
        $response->assertDontSee('Otros proyectos');
    }

    public function test_formulario_de_inversion_404_si_proyecto_no_visible_o_no_es_inversion()
    {
        $user = User::factory()->create();

        $oculto = $this->crearProyecto(['visible' => false]);
        $this->actingAs($user)->get(route('inversiones.form', $oculto))->assertNotFound();

        $portafolio = $this->crearProyecto(['tipo' => 'portafolio', 'slug' => 'portafolio-y']);
        $this->actingAs($user)->get(route('inversiones.form', $portafolio))->assertNotFound();
    }

    public function test_pagina_inversion_404_si_proyecto_no_visible_o_no_es_inversion()
    {
        $user = User::factory()->create();

        $oculto = $this->crearProyecto(['visible' => false]);
        $this->actingAs($user)->get(route('inversiones.create', $oculto))->assertNotFound();

        $portafolio = $this->crearProyecto(['tipo' => 'portafolio', 'slug' => 'portafolio-x']);
        $this->actingAs($user)->get(route('inversiones.create', $portafolio))->assertNotFound();
    }

    public function test_usuario_autenticado_puede_registrar_solicitud_de_inversion()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $response = $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'           => 'María Pérez',
            'email'            => 'maria@test.com',
            'telefono'         => '999111222',
            'dni'              => '12345678',
            'banco'            => 'BBVA',
            'cuenta_bancaria'  => '0011-0146-0100050182',
            'cci_bancario'     => '011-146-000100050182-83',
            'titular_cuenta'   => 'María Pérez',
            'monto'            => 5000,
            'metodo_pago_id'   => $metodo->id,
            'numero_operacion' => 'OP-123',
            'comprobante'      => UploadedFile::fake()->create('comprobante.pdf', 200, 'application/pdf'),
            'firma'            => UploadedFile::fake()->image('firma.png', 400, 200),
        ]);

        $response->assertRedirect(route('inversiones.gracias', $proyecto));

        $this->assertDatabaseHas('inversiones', [
            'proyecto_id'     => $proyecto->id,
            'user_id'         => $user->id,
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 5000,
            'estado'          => 'pendiente',
        ]);

        $inversion = Inversion::first();
        $this->assertNotNull($inversion->comprobante);
        Storage::disk('public')->assertExists($inversion->comprobante);

        $this->assertNotNull($inversion->firma);
        Storage::disk('public')->assertExists($inversion->firma);
        $this->assertStringContainsString('inversiones/firmas/', $inversion->firma);
    }

    public function test_usuario_autenticado_se_vincula_a_su_solicitud()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'Juan López',
            'email'           => 'juan@test.com',
            'banco'           => 'BCP',
            'cuenta_bancaria' => '191-12345678-0-01',
            'cci_bancario'    => '002-191-001234567801-10',
            'titular_cuenta'  => 'Juan López',
            'monto'           => 3000,
            'metodo_pago_id'  => $metodo->id,
        ])->assertRedirect(route('inversiones.gracias', $proyecto));

        $this->assertDatabaseHas('inversiones', [
            'user_id'   => $user->id,
            'proyecto_id' => $proyecto->id,
            'monto'     => 3000,
        ]);
    }

    public function test_datos_bancarios_son_obligatorios()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'         => 'María Pérez',
            'email'          => 'maria@test.com',
            'monto'          => 3000,
            'metodo_pago_id' => $metodo->id,
        ])->assertSessionHasErrors(['banco', 'cuenta_bancaria', 'cci_bancario', 'titular_cuenta']);

        $this->assertDatabaseCount('inversiones', 0);
    }

    public function test_datos_bancarios_se_guardan_con_la_solicitud()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 3000,
            'metodo_pago_id'  => $metodo->id,
        ])->assertRedirect(route('inversiones.gracias', $proyecto));

        $this->assertDatabaseHas('inversiones', [
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
        ]);
    }

    public function test_firma_es_opcional_y_se_guarda_sola()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 3000,
            'metodo_pago_id'  => $metodo->id,
            'firma'           => UploadedFile::fake()->image('firma.png', 400, 200),
        ])->assertRedirect(route('inversiones.gracias', $proyecto));

        $inversion = Inversion::first();
        $this->assertNotNull($inversion->firma);
        Storage::disk('public')->assertExists($inversion->firma);
        $this->assertStringContainsString('inversiones/firmas/', $inversion->firma);
    }

    public function test_firma_sin_archivo_no_bloquea_la_solicitud()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 3000,
            'metodo_pago_id'  => $metodo->id,
        ])->assertRedirect(route('inversiones.gracias', $proyecto));

        $this->assertDatabaseHas('inversiones', ['firma' => null]);
    }

    public function test_firma_con_formato_invalido_se_rechaza()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 3000,
            'metodo_pago_id'  => $metodo->id,
            'firma'           => UploadedFile::fake()->create('firma.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('firma');

        $this->assertDatabaseCount('inversiones', 0);
    }

    public function test_monto_superior_al_disponible_se_rechaza()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto(['inversion_monto_disponible' => 1000]);
        $metodo = $this->crearMetodo();

        $response = $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 5000,
            'metodo_pago_id'  => $metodo->id,
        ]);

        $response->assertSessionHasErrors('monto');
        $this->assertDatabaseCount('inversiones', 0);
    }

    public function test_monto_minimo_se_valida()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 10,
            'metodo_pago_id'  => $metodo->id,
        ])->assertSessionHasErrors('monto');

        $this->assertDatabaseCount('inversiones', 0);
    }

    public function test_solo_metodo_activo_es_aceptado()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $inactivo = MetodoPago::create([
            'clave' => 'bizum', 'nombre' => 'Bizum', 'activo' => false, 'datos' => [], 'orden' => 2,
        ]);

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'María Pérez',
            'email'           => 'maria@test.com',
            'banco'           => 'BBVA',
            'cuenta_bancaria' => '0011-0146-0100050182',
            'cci_bancario'    => '011-146-000100050182-83',
            'titular_cuenta'  => 'María Pérez',
            'monto'           => 5000,
            'metodo_pago_id'  => $inactivo->id,
        ])->assertSessionHasErrors('metodo_pago_id');

        $this->assertDatabaseCount('inversiones', 0);
    }

    public function test_mis_inversiones_requiere_autenticacion()
    {
        $this->get(route('inversiones.mis'))->assertRedirect(route('auth.google'));
    }

    public function test_login_google_regresa_a_la_url_intentada()
    {
        $user = User::factory()->create(['email' => 'juan@test.com']);
        $proyecto = $this->crearProyecto();

        $googleUser = new \Laravel\Socialite\Two\User;
        $googleUser->name = 'Juan';
        $googleUser->email = 'juan@test.com';

        Socialite::shouldReceive('driver')->with('google')->andReturn(
            Mockery::mock([
                'stateless' => Mockery::mock(['user' => $googleUser]),
            ])
        );

        $this->withSession(['url.intended' => route('inversiones.create', $proyecto)])
            ->get(route('auth.google.callback'))
            ->assertRedirect(route('inversiones.create', $proyecto));

        $this->assertAuthenticatedAs($user);
    }

    public function test_usuario_ve_solo_sus_inversiones()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        Inversion::create([
            'user_id' => $user->id, 'proyecto_id' => $proyecto->id,
            'nombre' => 'Juan', 'email' => 'juan@test.com', 'monto' => 1000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);
        Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'Otro', 'email' => 'otro@test.com', 'monto' => 2000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $this->actingAs($user)
            ->get(route('inversiones.mis'))
            ->assertOk()
            ->assertSee('1,000.00')
            ->assertSee('Planta Solar Trujillo')
            ->assertDontSee('2,000.00');
    }

    public function test_inversion_de_invitado_con_mismo_email_aparece_al_iniciar_sesion()
    {
        $user = User::factory()->create(['email' => 'maria@test.com']);
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 5000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'aprobado',
        ]);

        $this->actingAs($user)
            ->get(route('inversiones.mis'))
            ->assertOk()
            ->assertSee('5,000.00')
            ->assertSee('Planta Solar Trujillo');

        // La relación usuario-inversión queda establecida
        $this->assertDatabaseHas('inversiones', [
            'proyecto_id' => $proyecto->id,
            'email'       => 'maria@test.com',
            'user_id'     => $user->id,
        ]);
    }

    public function test_inversion_de_invitado_con_otro_email_no_se_vincula_al_usuario()
    {
        $user = User::factory()->create(['email' => 'maria@test.com']);
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'Otra', 'email' => 'otra@test.com', 'monto' => 3000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $this->actingAs($user)
            ->get(route('inversiones.mis'))
            ->assertOk()
            ->assertDontSee('3,000.00');

        $this->assertDatabaseHas('inversiones', [
            'proyecto_id' => $proyecto->id,
            'email'       => 'otra@test.com',
            'user_id'     => null,
        ]);
    }

    public function test_cliente_descarga_contrato_de_inversion_de_invitado_con_su_email()
    {
        Storage::fake('public');
        $user = User::factory()->create(['email' => 'jairo@test.com']);
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'Jairo', 'email' => 'jairo@test.com', 'monto' => 1000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'aprobado',
            'contrato_pdf' => 'inversiones/contratos/contrato-invitado.pdf',
        ]);
        Storage::disk('public')->put('inversiones/contratos/contrato-invitado.pdf', 'pdf');

        $this->actingAs($user)->get(route('inversiones.contrato', $inversion))->assertOk();
    }

    public function test_admin_puede_aprobar_y_descuenta_disponible()
    {
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto(); // disponible 60000, total 100000, 40%
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 10000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.inversiones.aprobar', $inversion))
            ->assertSessionHas('exito');

        $proyecto->refresh();
        $this->assertEquals(50000, (float) $proyecto->inversion_monto_disponible);
        $this->assertEquals(50, $proyecto->inversion_porcentaje_financiado);
        $this->assertDatabaseHas('inversiones', ['id' => $inversion->id, 'estado' => 'aprobado']);
    }

    public function test_admin_no_puede_aprobar_monto_mayor_al_disponible()
    {
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto(['inversion_monto_disponible' => 1000]);
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 5000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.inversiones.aprobar', $inversion))
            ->assertSessionHasErrors('inversion');

        $proyecto->refresh();
        $this->assertEquals(1000, (float) $proyecto->inversion_monto_disponible);
        $this->assertDatabaseHas('inversiones', ['id' => $inversion->id, 'estado' => 'pendiente']);
    }

    public function test_admin_puede_rechazar_pendiente()
    {
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 5000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $this->actingAs($admin)->post(route('admin.inversiones.rechazar', $inversion), [
            'notas_admin' => 'Documentación incompleta',
        ])->assertSessionHas('exito');

        $this->assertDatabaseHas('inversiones', ['id' => $inversion->id, 'estado' => 'rechazado', 'notas_admin' => 'Documentación incompleta']);

        // El disponible no cambia porque nunca se aprobó
        $proyecto->refresh();
        $this->assertEquals(60000, (float) $proyecto->inversion_monto_disponible);
    }

    public function test_rechazar_aprobada_devuelve_disponible()
    {
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto(); // disponible 60000
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 10000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'aprobado',
        ]);

        // Simular el estado post-aprobación
        $proyecto->update(['inversion_monto_disponible' => 50000, 'inversion_porcentaje_financiado' => 50]);

        $this->actingAs($admin)->post(route('admin.inversiones.rechazar', $inversion), [
            'notas_admin' => 'Cancelado',
        ])->assertSessionHas('exito');

        $proyecto->refresh();
        $this->assertEquals(60000, (float) $proyecto->inversion_monto_disponible);
        $this->assertEquals(40, $proyecto->inversion_porcentaje_financiado);
        $this->assertDatabaseHas('inversiones', ['id' => $inversion->id, 'estado' => 'rechazado']);
    }

    public function test_panel_admin_requiere_admin()
    {
        $user = User::factory()->create(['role' => 'cliente']);
        $admin = $this->crearAdmin();
        $metodo = $this->crearMetodo();
        $proyecto = $this->crearProyecto();
        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 1000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $this->actingAs($user)->get(route('admin.inversiones.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.inversiones.show', $inversion))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.inversiones.index'))->assertOk();
    }

    public function test_admin_subir_y_eliminar_contrato()
    {
        Storage::fake('public');
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto();

        $pdf = UploadedFile::fake()->create('contrato.pdf', 500, 'application/pdf');

        $this->actingAs($admin)
            ->post(route('admin.proyectos.contrato', $proyecto), ['contrato_pdf' => $pdf])
            ->assertSessionHas('exito');

        $proyecto->refresh();
        $this->assertNotNull($proyecto->contrato_pdf);
        Storage::disk('public')->assertExists($proyecto->contrato_pdf);

        $this->actingAs($admin)
            ->delete(route('admin.proyectos.contrato.eliminar', $proyecto))
            ->assertSessionHas('exito');

        $proyecto->refresh();
        $this->assertNull($proyecto->contrato_pdf);
    }

    public function test_roi_numero_se_extrae_del_string()
    {
        $proyecto = $this->crearProyecto(['inversion_roi' => '18.5%']);
        $this->assertEquals(18.5, $proyecto->inversion_roi_numero);

        $proyecto2 = $this->crearProyecto(['inversion_roi' => 'sin numero', 'slug' => 'planta-solar-trujillo-2']);
        $this->assertNull($proyecto2->inversion_roi_numero);
    }

    public function test_inversion_registra_un_pago_vinculado_con_ocr()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'           => 'María Pérez',
            'email'            => 'maria@test.com',
            'banco'            => 'BBVA',
            'cuenta_bancaria'  => '0011-0146-0100050182',
            'cci_bancario'     => '011-146-000100050182-83',
            'titular_cuenta'   => 'María Pérez',
            'monto'            => 5000,
            'metodo_pago_id'   => $metodo->id,
            'numero_operacion' => 'OP-456',
            'comprobante'      => UploadedFile::fake()->create('comprobante.pdf', 200, 'application/pdf'),
        ])->assertRedirect(route('inversiones.gracias', $proyecto));

        $inversion = Inversion::first();
        $this->assertNotNull($inversion);

        $this->assertDatabaseHas('pagos', [
            'inversion_id'     => $inversion->id,
            'pedido_id'        => null,
            'user_id'          => $user->id,
            'monto'            => 5000,
            'metodo_pago'      => $metodo->clave,
            'numero_operacion' => 'OP-456',
            'estado_pago'      => 'pendiente',
        ]);

        $pago = Pago::where('inversion_id', $inversion->id)->first();
        $this->assertNotNull($pago->ocr_resultado);
    }

    public function test_inversion_sin_comprobante_registra_pago_sin_ocr()
    {
        $user = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $this->actingAs($user)->post(route('inversiones.store', $proyecto), [
            'nombre'          => 'Juan López',
            'email'           => 'juan@test.com',
            'banco'           => 'BCP',
            'cuenta_bancaria' => '191-12345678-0-01',
            'cci_bancario'    => '002-191-001234567801-10',
            'titular_cuenta'  => 'Juan López',
            'monto'           => 3000,
            'metodo_pago_id'  => $metodo->id,
        ])->assertRedirect(route('inversiones.gracias', $proyecto));

        $this->assertDatabaseHas('pagos', [
            'inversion_id'     => Inversion::first()->id,
            'comprobante_pago' => null,
            'ocr_resultado'    => null,
            'estado_pago'      => 'pendiente',
        ]);
    }

    public function test_aprobar_inversion_confirma_el_pago_vinculado()
    {
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 10000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $pago = Pago::create([
            'inversion_id' => $inversion->id,
            'user_id'      => null,
            'monto'        => 10000,
            'metodo_pago'  => $metodo->clave,
            'estado_pago'  => 'pendiente',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.inversiones.aprobar', $inversion))
            ->assertSessionHas('exito');

        $pago->refresh();
        $this->assertEquals('aprobado', $pago->estado_pago);
        $this->assertNotNull($pago->fecha_pago);
        $this->assertEquals($admin->id, $pago->aprobado_por);
    }

    public function test_rechazar_inversion_rechaza_el_pago_vinculado()
    {
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 5000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $pago = Pago::create([
            'inversion_id' => $inversion->id,
            'user_id'      => null,
            'monto'        => 5000,
            'metodo_pago'  => $metodo->clave,
            'estado_pago'  => 'pendiente',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.inversiones.rechazar', $inversion), [
                'notas_admin' => 'Documentación incompleta',
            ])->assertSessionHas('exito');

        $pago->refresh();
        $this->assertEquals('rechazado', $pago->estado_pago);
        $this->assertEquals('Documentación incompleta', $pago->observaciones_admin);
    }

    public function test_admin_subir_y_descargar_contrato_de_inversion()
    {
        Storage::fake('public');
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 10000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'aprobado',
        ]);

        $pdf = UploadedFile::fake()->create('contrato-inversion.pdf', 500, 'application/pdf');

        $this->actingAs($admin)
            ->post(route('admin.inversiones.contrato', $inversion), ['contrato_pdf' => $pdf])
            ->assertSessionHas('exito');

        $inversion->refresh();
        $this->assertNotNull($inversion->contrato_pdf);
        Storage::disk('public')->assertExists($inversion->contrato_pdf);

        $this->actingAs($admin)
            ->get(route('admin.inversiones.contrato.descargar', $inversion))
            ->assertOk();
    }

    public function test_contrato_solo_se_puede_subir_si_inversion_aprobada()
    {
        Storage::fake('public');
        $admin = $this->crearAdmin();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $inversion = Inversion::create([
            'proyecto_id' => $proyecto->id,
            'nombre' => 'María', 'email' => 'maria@test.com', 'monto' => 10000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'pendiente',
        ]);

        $pdf = UploadedFile::fake()->create('contrato-inversion.pdf', 500, 'application/pdf');

        $this->actingAs($admin)
            ->post(route('admin.inversiones.contrato', $inversion), ['contrato_pdf' => $pdf])
            ->assertSessionHasErrors('inversion');

        $inversion->refresh();
        $this->assertNull($inversion->contrato_pdf);
    }

    public function test_cliente_descarga_contrato_solo_de_sus_inversiones()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $otro = User::factory()->create();
        $proyecto = $this->crearProyecto();
        $metodo = $this->crearMetodo();

        $mi = Inversion::create([
            'user_id' => $user->id, 'proyecto_id' => $proyecto->id,
            'nombre' => 'Juan', 'email' => 'juan@test.com', 'monto' => 1000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'aprobado',
            'contrato_pdf' => 'inversiones/contratos/contrato.pdf',
        ]);
        $ajena = Inversion::create([
            'user_id' => $otro->id, 'proyecto_id' => $proyecto->id,
            'nombre' => 'Otro', 'email' => 'otro@test.com', 'monto' => 1000,
            'metodo_pago_id' => $metodo->id, 'estado' => 'aprobado',
            'contrato_pdf' => 'inversiones/contratos/contrato-otro.pdf',
        ]);
        Storage::disk('public')->put('inversiones/contratos/contrato.pdf', 'pdf');
        Storage::disk('public')->put('inversiones/contratos/contrato-otro.pdf', 'pdf');

        $this->actingAs($user)->get(route('inversiones.contrato', $mi))->assertOk();
        $this->actingAs($user)->get(route('inversiones.contrato', $ajena))->assertForbidden();
    }
}
