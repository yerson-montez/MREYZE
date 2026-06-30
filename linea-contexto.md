# Línea de Contexto

**Proyecto:** Empresa (Laravel 12, PHP 8.4, MySQL)  
**Última actualización:** 30/06/2026

## Goal

Sistema completo de pedidos con pago Yape (50% adelanto + 50% contraentrega), gestión de colaboradores con comisiones, panel admin, y preparación para futuros métodos de pago (Plin, Mercado Pago, Culqi).

## Problema resuelto

Migración `2026_06_29_210031_create_pagos_table.php` fallaba con:
```
SQLSTATE[01000]: Warning: 1265 Data truncated for column 'estado' at row 1
```
Al ejecutar `DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM(...)")` porque existían registros con valor `'pendiente'` (antiguo ENUM) que no estaba en el nuevo ENUM.

## Solución aplicada

### 1. Migración corregida: `create_pagos_table`
- Guard `Schema::hasTable('pagos')` para ser idempotente
- `estado_pago` cambió de `->enum()` a `->string(50)`
- Se eliminó el `DB::statement` que alteraba `pedidos.estate` a ENUM

### 2. Nueva migración: `convert_enums_to_strings`
- **7 columnas ENUM convertidas a VARCHAR(50)**:
  | Tabla | Columna | Default |
  |-------|---------|---------|
  | pedidos | estado | pendiente_pago |
  | pagos | estado_pago | pendiente |
  | productos | categoria | iot |
  | productos | estado | disponible |
  | users | role | cliente |
  | colaboradores | estado | pendiente |
  | orders | status | pendiente |

- **Transformación de datos** antes de cambiar el tipo:
  - `pendiente` → `pendiente_pago`
  - `confirmado` → `pagado_parcial`

### 3. Vistas actualizadas
- `admin/colaboradores/show.blade.php` → badges con nuevos estados
- `colaboradores/mi-link.blade.php` → badges y `estado_label`

## Flujo de estados (pedidos)
```
pendiente_pago → pendiente_aprobacion → pagado_parcial → preparando → enviado → entregado
                                                                                    ↓
                                                                               cancelado
```

## Key Decisions
- Todos los ENUM migrados a VARCHAR para evitar modificar la estructura al agregar nuevos estados/métodos de pago/categorías
- La migración correctiva usa `DB::statement` + `ALTER TABLE MODIFY` por ser más confiable que `change()` para ENUM
- Se eliminan CHECK constraints de MySQL 8.0.19+ antes de MODIFY en tablas afectadas
- `metodos_pago` ya usa `string` (no ENUM), diseñado para Yape, Plin, BCP, Interbank, etc.
- Validación de estados se hace en PHP (FormRequest/Controller), no en la BD

## Next Steps
- Ejecutar `php artisan migrate` (la migración corregida + la nueva correctiva)
- Agregar notificaciones (email/in-app) al aprobar/rechazar pago
- Integrar Plin y otros métodos desde `metodos_pago` (sin migraciones)
- Reportes de pagos con filtros

## Relevant Files
- `database/migrations/2026_06_29_210031_create_pagos_table.php` (corregida)
- `database/migrations/2026_06_30_000001_convert_enums_to_strings.php` (nueva)
- `app/Models/Pedido.php`
- `app/Models/Pago.php`
- `app/Http/Controllers/PedidoController.php`
- `app/Http/Controllers/PagoController.php`
- `app/Http/Controllers/Admin/PagoAdminController.php`
- `app/Http/Controllers/Admin/PedidoAdminController.php`
- `routes/web.php`
- `resources/views/admin/pagos/index.blade.php`
- `resources/views/admin/pagos/show.blade.php`
- `resources/views/pagos/show.blade.php`
- `resources/views/pedidos/mis.blade.php`
