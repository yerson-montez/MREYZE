<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class TiendaSeeder extends Seeder
{
    public function run(): void
    {
        // ── MÉTODOS DE PAGO ─────────────────────────────────────────
        $metodos = [
            [
                'clave'  => 'yape',
                'nombre' => 'Yape',
                'emoji'  => '📲',
                'activo' => true,
                'orden'  => 1,
                'datos'  => [
                    'numero'   => '999 000 111',
                    'alias'    => 'MiEmpresa',
                    'titular'  => 'Juan Pérez',
                ],
            ],
            [
                'clave'  => 'plin',
                'nombre' => 'Plin',
                'emoji'  => '💸',
                'activo' => true,
                'orden'  => 2,
                'datos'  => [
                    'numero'   => '999 000 222',
                    'titular'  => 'Juan Pérez',
                ],
            ],
            [
                'clave'  => 'bcp',
                'nombre' => 'BCP',
                'emoji'  => '🏦',
                'activo' => true,
                'orden'  => 3,
                'datos'  => [
                    'numero'   => '191-12345678-0-12',
                    'cci'      => '002-191-001234567890-12',
                    'titular'  => 'Juan Pérez',
                ],
            ],
            [
                'clave'  => 'interbank',
                'nombre' => 'Interbank',
                'emoji'  => '🏛️',
                'activo' => false,
                'orden'  => 4,
                'datos'  => [
                    'numero'   => '200-1234567890',
                    'cci'      => '003-200-001234567890-39',
                    'titular'  => 'Juan Pérez',
                ],
            ],
            [
                'clave'  => 'bbva',
                'nombre' => 'BBVA',
                'emoji'  => '🔵',
                'activo' => false,
                'orden'  => 5,
                'datos'  => [
                    'numero'   => '0011-0111-01-00123456',
                    'cci'      => '011-011-000112345600-16',
                    'titular'  => 'Juan Pérez',
                ],
            ],
            [
                'clave'  => 'paypal',
                'nombre' => 'PayPal',
                'emoji'  => '🅿️',
                'activo' => false,
                'orden'  => 6,
                'datos'  => [
                    'alias'    => 'pagos@miempresa.com',
                ],
            ],
            [
                'clave'  => 'efectivo',
                'nombre' => 'Efectivo / Contra entrega',
                'emoji'  => '💵',
                'activo' => true,
                'orden'  => 7,
                'datos'  => [],
            ],
            [
                'clave'  => 'tarjeta',
                'nombre' => 'Tarjeta (Visa / Mastercard)',
                'emoji'  => '💳',
                'activo' => false,
                'orden'  => 8,
                'datos'  => [],
            ],
        ];

        foreach ($metodos as $m) {
            MetodoPago::updateOrCreate(['clave' => $m['clave']], $m);
        }

        // ── PRODUCTOS DEMO ───────────────────────────────────────────
        $productos = [
            [
                'nombre'      => 'Kit ESP32 + Sensores IoT',
                'descripcion' => 'Kit completo con ESP32, sensores de temperatura, humedad y presión. Ideal para proyectos de monitoreo.',
                'categoria'   => 'iot',
                'precio'      => 85.00,
                'estado'      => 'nuevo',
                'emoji'       => '📡',
                'activo'      => true,
                'orden'       => 1,
            ],
            [
                'nombre'          => 'Dashboard IoT Web (Licencia)',
                'descripcion'     => 'Plataforma web lista para desplegar. Visualización de sensores, alertas y reportes en tiempo real.',
                'categoria'       => 'software',
                'precio'          => 299.00,
                'precio_anterior' => 380.00,
                'estado'          => 'disponible',
                'emoji'           => '💻',
                'activo'          => true,
                'orden'           => 2,
            ],
            [
                'nombre'      => 'Kit Automatización Básica',
                'descripcion' => 'Relés, contactores, botones y cableado para armar tu primer tablero de control industrial.',
                'categoria'   => 'kits',
                'precio'      => 150.00,
                'estado'      => 'disponible',
                'emoji'       => '⚙️',
                'activo'      => true,
                'orden'       => 3,
            ],
            [
                'nombre'      => 'Módulo Raspberry Pi 4 + Case',
                'descripcion' => 'Raspberry Pi 4 Model B (4GB RAM) con case de aluminio y disipadores. Perfecto para servidores IoT.',
                'categoria'   => 'iot',
                'precio'      => 320.00,
                'estado'      => 'agotado',
                'emoji'       => '🔌',
                'activo'      => true,
                'orden'       => 4,
            ],
        ];

        foreach ($productos as $p) {
            Producto::updateOrCreate(['nombre' => $p['nombre']], $p);
        }
    }
}