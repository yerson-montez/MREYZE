<?php

namespace App\Console\Commands;

use App\Services\TesseractOcrService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OcrTestCommand extends Command
{
    protected $signature = 'ocr:test {image : Nombre del archivo en storage/app/public/comprobantes/}';
    protected $description = 'Prueba diagnóstica de Tesseract OCR antes de producción';

    public function handle(TesseractOcrService $ocrService): int
    {
        $imageName = $this->argument('image');
        $relativePath = 'comprobantes/' . $imageName;
        $fullPath = Storage::disk('public')->path($relativePath);

        $this->line(str_repeat('═', 56));
        $this->info('  DIAGNÓSTICO TESSERACT OCR');
        $this->line(str_repeat('═', 56));
        $this->newLine();

        // 1. Validar archivo
        if (! file_exists($fullPath)) {
            $this->error('  ✗ Imagen no encontrada');
            $this->line('    Ruta: ' . $fullPath);
            return self::FAILURE;
        }

        $this->line('  ┌─ Imagen');
        $this->line('  ├─ Ruta:        ' . $fullPath);
        $this->line('  ├─ Tamaño:      ' . number_format(filesize($fullPath)) . ' bytes');
        $this->line('  ├─ MIME:        ' . (mime_content_type($fullPath) ?: 'desconocido'));

        $info = @getimagesize($fullPath);
        if ($info !== false) {
            $this->line('  ├─ Resolución:  ' . $info[0] . ' × ' . $info[1] . ' px');
        }

        $this->newLine();

        // 2. Procesar OCR
        $this->line('  ┌─ Tesseract OCR');
        $this->output->write('  ├─ Estado:      Procesando...');
        $tiempoInicio = microtime(true);

        try {
            $resultado = $ocrService->extraerTexto($relativePath);
        } catch (\Throwable $e) {
            $this->output->write("\r  ├─ Estado:      \xE2\x9C\x97 Falló\n");
            $this->line('  └─ Error:       ' . $e->getMessage());
            $this->newLine();
            $this->error('  RESULTADO FINAL: ERROR');
            return self::FAILURE;
        }

        $tiempoTotal = round((microtime(true) - $tiempoInicio) * 1000);
        $this->output->write("\r  ├─ Estado:      \xE2\x9C\x93 Completado\n");
        $this->line('  ├─ Tiempo:      ' . $tiempoTotal . ' ms');

        if ($resultado === null) {
            $this->line('  └─ Resultado:   No se pudo extraer texto');
            $this->newLine();
            $this->error('  RESULTADO FINAL: OCR FALLIDO');
            $this->line('  Posibles causas:');
            $this->line('  - La imagen no contiene texto legible');
            $this->line('  - La imagen tiene muy baja resolución');
            $this->line('  - El idioma spa no está instalado en Tesseract');
            $this->line('  - Revisa storage/logs/laravel.log para más detalles');
            return self::FAILURE;
        }

        $this->line('  ├─ Caracteres:  ' . strlen($resultado->textoRaw));
        $this->line('  ├─ Líneas:      ' . (substr_count($resultado->textoRaw, "\n") + 1));
        $this->line('  └─ Confianza:   ' . ($resultado->confianzaGlobal * 100) . '%');
        $this->newLine();

        // 3. Texto completo detectado
        $this->line('  ┌─ Texto completo detectado');
        $this->line('  │');
        foreach (explode("\n", $resultado->textoRaw) as $line) {
            $this->line('  │  ' . $line);
        }
        $this->line('  └' . str_repeat('─', 53));
        $this->newLine();

        // 4. Datos extraídos
        $this->line('  ┌─ Datos extraídos');
        $codigo = $resultado->codigoOperacion;
        $monto  = $resultado->monto;
        $fecha  = $resultado->fecha;
        $this->line('  ├─ Operación:   ' . ($codigo ?? '—'));
        $this->line('  ├─ Monto:       ' . ($monto !== null ? 'S/ ' . number_format($monto, 2) : '—'));
        $this->line('  ├─ Fecha:       ' . ($fecha ?? '—'));
        $this->line('  └─ Receptor:    ' . ($resultado->receptor ?? '—'));
        $this->newLine();

        // 5. Resultado final
        $this->line(str_repeat('─', 56));
        $todosEncontrados = $codigo !== null && $monto !== null && $fecha !== null;
        if ($todosEncontrados) {
            $this->info('  RESULTADO FINAL: ✅ OK — Todos los campos extraídos');
        } else {
            $this->warn('  RESULTADO FINAL: ⚠️  PARCIAL — Algunos campos no se encontraron');
        }
        $this->line('  Confianza: ' . ($resultado->confianzaGlobal * 100) . '%');
        $this->line('  Tiempo:    ' . $tiempoTotal . ' ms');
        $this->line(str_repeat('─', 56));

        return $todosEncontrados ? self::SUCCESS : self::FAILURE;
    }
}
