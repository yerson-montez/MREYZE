<?php

namespace App\Services;

use App\DTOs\ResultadoOcr;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VisionOcrService
{
    private const LLAVES_REQUERIDAS = ['project_id', 'client_email', 'private_key'];

    private ?ImageAnnotatorClient $client = null;

    public function extraerTexto(string $relativePath): ?ResultadoOcr
    {
        $fullPath = Storage::disk('public')->path($relativePath);
        $hashSha256 = null;
        $tiempoInicio = microtime(true);

        if (! file_exists($fullPath)) {
            Log::error('OCR: archivo de imagen no encontrado', [
                'path'      => $relativePath,
                'full_path' => $fullPath,
            ]);
            return null;
        }

        $imageContent = file_get_contents($fullPath);
        $hashSha256 = hash('sha256', $imageContent);
        $mimeType = mime_content_type($fullPath);
        $imageSize = $this->obtenerDimensiones($fullPath, $mimeType);

        Log::info('OCR: iniciando procesamiento de imagen', [
            'path'          => $relativePath,
            'full_path'     => $fullPath,
            'hash'          => $hashSha256,
            'size_bytes'    => strlen($imageContent),
            'mime_type'     => $mimeType,
            'dimensiones'   => $imageSize,
        ]);

        try {
            $errorCredencial = $this->validarCredenciales();

            if ($errorCredencial !== null) {
                Log::error($errorCredencial['log'], $errorCredencial['context']);
                return null;
            }

            $batchRequest = (new BatchAnnotateImagesRequest())
                ->setRequests([$this->crearRequest($imageContent)]);
            $response = $this->client()->batchAnnotateImages($batchRequest);
            $tiempoApi = round((microtime(true) - $tiempoInicio) * 1000);

            Log::info('OCR: respuesta de Google Vision recibida', [
                'path'           => $relativePath,
                'hash'           => $hashSha256,
                'tiempo_respuesta_ms' => $tiempoApi,
            ]);

            $responses = $response->getResponses();
            unset($response);

            if ($responses->count() === 0) {
                Log::warning('OCR: Google Vision respondió sin resultados', [
                    'path' => $relativePath, 'hash' => $hashSha256,
                    'tiempo_respuesta_ms' => $tiempoApi,
                ]);
                return null;
            }

            $annotation = $responses[0];

            if ($annotation->getError()) {
                Log::error('OCR: Google Vision API retornó error', [
                    'code'    => $annotation->getError()->getCode(),
                    'message' => $annotation->getError()->getMessage(),
                    'hash'    => $hashSha256,
                    'tiempo_respuesta_ms' => $tiempoApi,
                ]);
                return null;
            }

            $fullText = $this->extraerTextoCompleto($annotation);

            if ($fullText === null) {
                Log::warning('OCR: no se detectó texto en la imagen', [
                    'path' => $relativePath, 'hash' => $hashSha256,
                    'tiempo_respuesta_ms' => $tiempoApi,
                ]);
                return null;
            }

            Log::info('OCR: texto OCR completo recibido de Google', [
                'hash'     => $hashSha256,
                'chars'    => strlen($fullText),
                'lines'    => substr_count($fullText, "\n") + 1,
                'texto'    => $fullText,
                'tiempo_respuesta_ms' => $tiempoApi,
            ]);

            $confianza = $this->extraerConfianzaReal($annotation);
            unset($annotation);

            $resultado = $this->parseResultado($fullText, $confianza, $hashSha256);

            Log::info('OCR: texto extraído correctamente', [
                'hash'      => $hashSha256,
                'confianza' => $confianza,
                'chars'     => strlen($fullText),
                'tiempo_total_ms' => round((microtime(true) - $tiempoInicio) * 1000),
                'campos'    => array_filter([
                    'codigo'   => $resultado->codigoOperacion,
                    'monto'    => $resultado->monto,
                    'fecha'    => $resultado->fecha,
                    'receptor' => $resultado->receptor,
                ]),
            ]);

            return $resultado;
        } catch (\Google\ApiCore\ApiException $e) {
            Log::error('OCR: Google Cloud Vision API rechazó la solicitud', [
                'error'     => $e->getMessage(),
                'code'      => $e->getCode(),
                'status'    => $e->getStatus(),
                'reason'    => $e->getReason(),
                'hash'      => $hashSha256,
                'tiempo_total_ms' => round((microtime(true) - $tiempoInicio) * 1000),
            ]);
            throw $e;
        } catch (\Throwable $e) {
            Log::error('OCR: excepción interna durante procesamiento', [
                'error'   => $e->getMessage(),
                'class'   => get_class($e),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'hash'    => $hashSha256,
            ]);
            return null;
        } finally {
            unset($imageContent);
        }
    }

    private function obtenerDimensiones(string $fullPath, string $mimeType): ?string
    {
        if (str_starts_with($mimeType, 'image/')) {
            $info = @getimagesize($fullPath);
            if ($info !== false) {
                return $info[0] . 'x' . $info[1];
            }
        }
        return null;
    }

    private function crearRequest(string $imageContent): AnnotateImageRequest
    {
        $image = new Image();
        $image->setContent($imageContent);

        $feature = (new Feature())->setType(Type::TEXT_DETECTION);

        return (new AnnotateImageRequest())
            ->setImage($image)
            ->setFeatures([$feature]);
    }

    private function client(): ImageAnnotatorClient
    {
        if ($this->client === null) {
            $ruta = config('services.google.vision_credentials');

            $this->client = new ImageAnnotatorClient([
                'keyFilePath' => $ruta,
            ]);
        }

        return $this->client;
    }

    private function validarCredenciales(): ?array
    {
        $ruta = config('services.google.vision_credentials');

        if (empty($ruta)) {
            return [
                'log'     => 'OCR: GOOGLE_APPLICATION_CREDENTIALS no está configurada en .env',
                'context' => ['key' => 'services.google.vision_credentials'],
            ];
        }

        if (! file_exists($ruta)) {
            return [
                'log'     => 'OCR: el archivo de credenciales no existe en la ruta configurada',
                'context' => ['ruta' => $ruta],
            ];
        }

        if (! is_readable($ruta)) {
            return [
                'log'     => 'OCR: el archivo de credenciales no tiene permisos de lectura',
                'context' => ['ruta' => $ruta],
            ];
        }

        $contenido = file_get_contents($ruta);
        $json = json_decode($contenido, true);
        unset($contenido);

        if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
            return [
                'log'     => 'OCR: el archivo de credenciales no contiene un JSON válido',
                'context' => ['ruta' => $ruta, 'error_json' => json_last_error_msg()],
            ];
        }

        $faltantes = [];
        foreach (self::LLAVES_REQUERIDAS as $llave) {
            if (empty($json[$llave])) {
                $faltantes[] = $llave;
            }
        }

        if (! empty($faltantes)) {
            return [
                'log'     => 'OCR: el JSON de credenciales no contiene las llaves esenciales',
                'context' => ['ruta' => $ruta, 'llaves_faltantes' => $faltantes],
            ];
        }

        return null;
    }

    private function extraerTextoCompleto($annotation): ?string
    {
        $fullTextAnnot = $annotation->getFullTextAnnotation();
        if ($fullTextAnnot !== null) {
            $text = $fullTextAnnot->getText();
            if (! empty($text)) {
                return $text;
            }
        }

        $texts = $annotation->getTextAnnotations();
        if ($texts->count() > 0) {
            return $texts[0]->getDescription();
        }

        return null;
    }

    private function extraerConfianzaReal($annotation): float
    {
        $fullTextAnnot = $annotation->getFullTextAnnotation();
        if ($fullTextAnnot === null) {
            return 0.0;
        }

        $pages = $fullTextAnnot->getPages();
        if ($pages->count() === 0) {
            return 0.0;
        }

        $totalConfianza = 0.0;
        $pageCount = 0;

        foreach ($pages as $page) {
            $pageConfidence = $page->getConfidence();
            if ($pageConfidence !== null) {
                $totalConfianza += $pageConfidence;
                $pageCount++;
            }
        }

        return $pageCount > 0 ? round($totalConfianza / $pageCount, 4) : 0.0;
    }

    private function parseResultado(string $fullText, float $confianza, string $hashSha256): ResultadoOcr
    {
        $lines = explode("\n", $fullText);

        return new ResultadoOcr(
            codigoOperacion: $this->extraerCodigoOperacion($lines),
            monto:           $this->extraerMonto($lines),
            fecha:           $this->extraerFecha($lines),
            receptor:        $this->extraerReceptor($lines),
            confianzaGlobal: $confianza,
            textoRaw:        $fullText,
            hashSha256:      $hashSha256,
        );
    }

    private function extraerCodigoOperacion(array $lines): ?string
    {
        foreach ($lines as $line) {
            if (preg_match('/C[óo]digo\s*(?:de\s*)?operaci[óo]n[:\s]*([A-Z0-9]{6,})/i', $line, $m)) {
                return $m[1];
            }
            if (preg_match('/C[óo]digo[:\s]*([A-Z0-9]{6,})/i', $line, $m)) {
                return $m[1];
            }
        }
        return null;
    }

    private function extraerMonto(array $lines): ?float
    {
        foreach ($lines as $line) {
            if (preg_match('/[Mm]onto[:\s]*S\/?\s*([0-9]+(?:\.[0-9]{1,2})?)/', $line, $m)) {
                return (float) $m[1];
            }
            if (preg_match('/S\/?\s*([0-9]+\.[0-9]{2})\b/', $line, $m)) {
                return (float) $m[1];
            }
            if (preg_match('/^S\/?\s*([0-9]+\.[0-9]{2})\s*$/', $line, $m)) {
                return (float) $m[1];
            }
        }
        return null;
    }

    private function extraerFecha(array $lines): ?string
    {
        foreach ($lines as $line) {
            if (preg_match('/\b(\d{2}[\/-]\d{2}[\/-]\d{4})\b/', $line, $m)) {
                return $m[1];
            }
            if (preg_match('/\b(\d{4}[\/-]\d{2}[\/-]\d{2})\b/', $line, $m)) {
                return $m[1];
            }
        }
        return null;
    }

    private function extraerReceptor(array $lines): ?string
    {
        foreach ($lines as $line) {
            if (preg_match('/[Pp]agado\s+a[:\s]*(.+)/i', $line, $m)) {
                return trim($m[1]);
            }
            if (preg_match('/[Rr]eceptor[:\s]*(.+)/i', $line, $m)) {
                return trim($m[1]);
            }
            if (preg_match('/[Pp]ara[:\s]*(.+)/i', $line, $m)) {
                return trim($m[1]);
            }
        }
        return null;
    }
}
