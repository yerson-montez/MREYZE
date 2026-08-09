<?php

namespace App\Imports;

use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    $codigo = trim($row['codigo'] ?? '');

    $nombre = trim(
        $row['nombre']
        ?? $row['acc_muebles_de_computo']
        ?? 'Producto Deltron'
    );


        $stock = $row['stock'] ?? 0;

        if (is_string($stock)) {
            $stock = preg_replace('/[^0-9]/', '', $stock);
        }


        // Imagen principal
        $imagenPrincipal = 
            $row['imagen1']
            ?? $row['imagen']
            ?? $row['icono_imagen']
            ?? null;


        // Precio de distribuidor (PREC US $ del Excel) + margen automático
        $precioDistribuidor = (float)(
            $row['prec_us']
            ?? $row['prec_us_']
            ?? 0
        );

        $producto = new Producto([

            'codigo' => $codigo,

            'nombre' => $nombre,

            'descripcion' =>
                'Marca: '.($row['marca'] ?? '').
                ' Garantía: '.($row['garan'] ?? ''),

            'precio_distribuidor' => $precioDistribuidor,

            'precio' => Producto::calcularPrecioVenta($precioDistribuidor),

            'stock' => (int)$stock,

            'categoria' => $row['categoria'] ?? 'iot',

            'estado' => ((int)$stock > 0)
                ? 'disponible'
                : 'agotado',

            'activo' => true,

            'icono_imagen' => $imagenPrincipal,

            'slug' => Str::slug($codigo.'-'.$nombre),

        ]);


        // Guardar imágenes adicionales después de crear producto
        $producto->save();


        $imagenes = [
            0 => $row['imagen1'] ?? null,
            1 => $row['imagen2'] ?? null,
            2 => $row['imagen3'] ?? null,
            3 => $row['imagen4'] ?? null,
        ];


        foreach ($imagenes as $orden => $imagen) {

            if (!empty($imagen)) {

                ProductoImagen::create([
                    'producto_id' => $producto->id,
                    'ruta' => trim($imagen),
                    'orden' => $orden,
                    'es_principal' => $orden === 0,
                ]);

            }

        }


        return null;
    }
}