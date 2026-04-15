<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Producto;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // ── Administrador 1 ──────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@mreyze.com'],
            [
                'name'     => 'Admin Principal',
                'email'    => 'admin@mreyze.com',
                'password' => Hash::make('Admin@12345'),
                'role'     => 'admin',
            ]
        );

        // ── Administrador 2 ──────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'yerson@mreyze.com'],
            [
                'name'     => 'Yerson Montez',
                'email'    => 'yerson@mreyze.com',
                'password' => Hash::make('Yerson@12345'),
                'role'     => 'admin',
            ]
        );

        // ── Productos de ejemplo ─────────────────────────────────────
        $productos = [
            [
                'nombre'        => 'Kit ESP32 + Sensores IoT',
                'descripcion'   => 'Kit completo con ESP32, sensores de temperatura, humedad y presión. Ideal para proyectos de monitoreo.',
                'precio'        => 85.00,
                'precio_anterior' => null,
                'stock'         => 15,
                'categoria'     => 'iot',
                'estado'        => 'nuevo',
                'icono_imagen' => null,
                'icono_color'   => 'rgba(0,212,255,0.12)',
                'slug'          => 'esp32-kit',
                'activo'        => true,
            ],
            [
                'nombre'        => 'Dashboard IoT Web (Licencia)',
                'descripcion'   => 'Plataforma web lista para desplegar. Visualización de sensores, alertas y reportes en tiempo real.',
                'precio'        => 299.00,
                'precio_anterior' => 380.00,
                'stock'         => 999,
                'categoria'     => 'software',
                'estado'        => 'disponible',
                'icono_imagen' => null,
                'icono_color'   => 'rgba(20,184,166,0.12)',
                'slug'          => 'dashboard-iot',
                'activo'        => true,
            ],
            [
                'nombre'        => 'Kit Automatización Básica',
                'descripcion'   => 'Relés, contactores, botones y cableado para armar tu primer tablero de control industrial.',
                'precio'        => 150.00,
                'precio_anterior' => null,
                'stock'         => 8,
                'categoria'     => 'kits',
                'estado'        => 'disponible',
                'icono_imagen' => null,
                'icono_color'   => 'rgba(30,77,153,0.25)',
                'slug'          => 'kit-automatizacion',
                'activo'        => true,
            ],
            [
                'nombre'        => 'Módulo Raspberry Pi 4 + Case',
                'descripcion'   => 'Raspberry Pi 4 Model B (4GB RAM) con case de aluminio y disipadores. Perfecto para servidores IoT.',
                'precio'        => 320.00,
                'precio_anterior' => null,
                'stock'         => 0,
                'categoria'     => 'iot',
                'estado'        => 'agotado',
                'icono_imagen' => null,
                'icono_color'   => 'rgba(99,102,241,0.12)',
                'slug'          => 'raspberry-pi4',
                'activo'        => true,
            ],
        ];

        foreach ($productos as $p) {
            Producto::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
