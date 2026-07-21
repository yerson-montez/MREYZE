<?php

namespace App\Console\Commands;

use App\Services\TesseractOcrService;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OcrDiagnoseCommand extends Command
{
    protected $signature = 'ocr:diagnose {--test-image= : Ruta de imagen de prueba relativa a storage/app/public}';
    protected $description = 'Diagnóstico completo del módulo OCR (credenciales, GCP, Tesseract, test opcional)';

    private int $passed = 0;
    private int $failed = 0;

    public function handle(TesseractOcrService $tesseractService): int
    {
        $this->info('=== Diagnóstico del Módulo OCR ===');
        $this->newLine();

        $this->revisarEntorno();
        $this->revisarArchivoCredenciales();
        $this->revisarClienteGcp();
        $this->revisarTesseract($tesseractService);
        $this->revisarCola();
        $this->revisarMigracion();

        $testImage = $this->option('test-image');
        if ($testImage) {
            $this->testOcr($testImage);
        }

        $this->newLine();
        $this->components->twoColumnDetail('Pruebas superadas', (string) $this->passed);
        $this->components->twoColumnDetail('Pruebas fallidas', (string) $this->failed);

        return $this->failed === 0 ? static::SUCCESS : static::FAILURE;
    }

    private function passStep(string $desc, string $detail = ''): void
    {
        $this->passed++;
        $this->components->twoColumnDetail("  \xE2\x9C\x93 {$desc}", $detail);
    }

    private function failStep(string $desc, string $detail = ''): void
    {
        $this->failed++;
        $this->components->twoColumnDetail("  \xE2\x9C\x97 {$desc}", $detail);
    }

    private function seccion(string $titulo): void
    {
        $this->newLine();
        $this->info($titulo);
        $this->line(str_repeat('-', 50));
    }

    private function revisarEntorno(): void
    {
        $this->seccion('1. Entorno');

        $creds = config('services.google.vision_credentials');
        if ($creds) {
            $this->passStep('services.google.vision_credentials', $creds);
        } else {
            $this->failStep('services.google.vision_credentials', 'NO CONFIGURADO');
        }
    }

    private function revisarArchivoCredenciales(): void
    {
        $this->seccion('2. Archivo de credenciales');

        $ruta = config('services.google.vision_credentials');
        if (! $ruta) {
            $this->failStep('Ruta configurada', 'vacía');
            return;
        }

        if (! file_exists($ruta)) {
            $this->failStep('Archivo existe', "NO — {$ruta}");
            return;
        }
        $this->passStep('Archivo existe', $ruta);

        if (! is_readable($ruta)) {
            $this->failStep('Archivo legible', 'NO');
            return;
        }
        $this->passStep('Archivo legible', 'sí');

        $json = json_decode(file_get_contents($ruta), true);
        if ($json === null) {
            $this->failStep('JSON válido', json_last_error_msg());
            return;
        }
        $this->passStep('JSON válido', 'sí');

        $requeridas = ['project_id', 'client_email', 'private_key', 'private_key_id'];
        foreach ($requeridas as $key) {
            if (empty($json[$key])) {
                $this->failStep("Llave '{$key}' presente", 'NO');
            } else {
                $val = $key === 'private_key' ? substr($json[$key], 0, 20) . '...' : $json[$key];
                $this->passStep("Llave '{$key}' presente", $val);
            }
        }
    }

    private function revisarClienteGcp(): void
    {
        $this->seccion('3. Conexión con Google Cloud Vision');

        $ruta = config('services.google.vision_credentials');
        if (! $ruta || ! file_exists($ruta)) {
            $this->failStep('Cliente', 'credenciales no disponibles');
            return;
        }

        try {
            $client = new ImageAnnotatorClient(['keyFilePath' => $ruta]);
            $client->close();
            $this->passStep('Cliente GCP instanciado', 'sin errores');
        } catch (\Throwable $e) {
            $this->failStep('Cliente GCP', $e->getMessage());
        }
    }

    private function revisarTesseract(TesseractOcrService $tesseractService): void
    {
        $this->seccion('4. Tesseract OCR (local)');

        $binary = config('services.tesseract.binary_path');
        if (! $binary || ! file_exists($binary)) {
            $this->failStep('Ejecutable', $binary ? "NO encontrado en {$binary}" : 'NO CONFIGURADO');
            return;
        }
        $this->passStep('Ejecutable', $binary);

        $version = $tesseractService->testConnection();
        if (str_starts_with($version, 'Error')) {
            $this->failStep('Versión', $version);
            return;
        }
        $this->passStep('Versión', $version);
    }

    private function revisarCola(): void
    {
        $this->seccion('5. Cola OCR');

        $connection = config('queue.default');
        $this->passStep('Conexión de cola', $connection);

        $table = config('queue.connections.database.table', 'jobs');
        $this->passStep('Tabla de jobs', $table);
    }

    private function revisarMigracion(): void
    {
        $this->seccion('6. Migración');

        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('pagos');
        $ocrColumns = ['ocr_resultado', 'ocr_datos', 'ocr_confianza', 'ocr_procesado_at', 'ocr_intentos', 'ocr_hash'];
        $presentes = array_intersect($ocrColumns, $columns);
        $faltantes = array_diff($ocrColumns, $columns);

        $this->passStep('Columnas OCR en pagos', count($presentes) . ' de ' . count($ocrColumns));

        if (! empty($faltantes)) {
            $this->failStep('Columnas faltantes', implode(', ', $faltantes));
        }
    }

    private function testOcr(string $relativePath): void
    {
        $this->seccion('7. Test OCR con imagen');

        $fullPath = Storage::disk('public')->path($relativePath);
        if (! file_exists($fullPath)) {
            $this->failStep('Imagen de prueba', "no encontrada en {$fullPath}");
            return;
        }
        $this->passStep('Imagen de prueba', $fullPath);

        try {
            $service = app(\App\Services\TesseractOcrService::class);
            $resultado = $service->extraerTexto($relativePath);

            if ($resultado === null) {
                $this->failStep('OCR result', 'no se pudo extraer texto');
                return;
            }

            $this->passStep('Texto extraído', mb_substr($resultado->textoRaw, 0, 80) . '...');
            $this->passStep('Confianza', (string) $resultado->confianzaGlobal);
            $this->passStep('Hash', $resultado->hashSha256);
            $this->passStep('Código operación', $resultado->codigoOperacion ?? '(no detectado)');
            $this->passStep('Monto', $resultado->monto !== null ? 'S/ ' . number_format($resultado->monto, 2) : '(no detectado)');
            $this->passStep('Fecha', $resultado->fecha ?? '(no detectada)');
            $this->passStep('Receptor', $resultado->receptor ?? '(no detectado)');
        } catch (\Throwable $e) {
            $this->failStep('Excepción en OCR', $e->getMessage());
        }
    }
}
