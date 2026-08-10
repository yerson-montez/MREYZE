<?php

namespace App\Services;

use App\DTOs\ResultadoOcr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;
use thiagoalessio\TesseractOCR\TesseractOcrException;

class TesseractOcrService
{
    private const MIN_DIMENSION = 1000;
    private const UPSAMPLE_THRESHOLD = 800;
    private const BINARIZE_SAMPLES = 5000;

    public function extraerTexto(string $relativePath): ?ResultadoOcr
    {
        $fullPath = Storage::disk('public')->path($relativePath);
        $hashSha256 = null;
        $tiempoInicio = microtime(true);
        $tempPath = null;

        if (! file_exists($fullPath)) {
            Log::error('TesseractOCR: archivo de imagen no encontrado', [
                'path'      => $relativePath,
                'full_path' => $fullPath,
            ]);
            return null;
        }

        $imageContent = file_get_contents($fullPath);
        $hashSha256 = hash('sha256', $imageContent);
        $mimeType = mime_content_type($fullPath);
        $imageSize = $this->obtenerDimensiones($fullPath, $mimeType);
        $sizeBytes = strlen($imageContent);

        Log::info('TesseractOCR: iniciando procesamiento', [
            'path'        => $relativePath,
            'full_path'   => $fullPath,
            'hash'        => $hashSha256,
            'size_bytes'  => $sizeBytes,
            'mime_type'   => $mimeType,
            'dimensiones' => $imageSize,
        ]);

        try {
            $tempPath = $this->preprocesarImagen($fullPath, $mimeType);

            if ($tempPath === null) {
                Log::warning('TesseractOCR: preprocesamiento falló, usando original', [
                    'hash' => $hashSha256,
                ]);
                $tempPath = $fullPath;
            }

            $tesseract = new TesseractOCR($tempPath);
            $tesseract->lang('spa');
            $tesseract->psm(3);
            // $tesseract->withoutTempFiles();

            $binaryPath = config('services.tesseract.binary_path');
            if ($binaryPath) {
                $tesseract->executable($binaryPath);
            }

            $text = $tesseract->run(120);

            $tiempoTotal = round((microtime(true) - $tiempoInicio) * 1000);

            if ($text === null || trim($text) === '') {
                Log::warning('TesseractOCR: no se detectó texto en la imagen', [
                    'path'      => $relativePath,
                    'hash'      => $hashSha256,
                    'tiempo_ms' => $tiempoTotal,
                ]);
                return null;
            }

            Log::info('TesseractOCR: texto OCR recibido', [
                'hash'      => $hashSha256,
                'chars'     => strlen($text),
                'lines'     => substr_count($text, "\n") + 1,
                'texto'     => $text,
                'tiempo_ms' => $tiempoTotal,
            ]);

            $confianza = $this->calcularConfianza($text);
            $resultado = $this->parseResultado($text, $confianza, $hashSha256);

            Log::info('TesseractOCR: campos extraídos', [
                'hash'      => $hashSha256,
                'confianza' => $confianza,
                'chars'     => strlen($text),
                'tiempo_ms' => $tiempoTotal,
                'campos'    => array_filter([
                    'codigo'   => $resultado->codigoOperacion,
                    'monto'    => $resultado->monto,
                    'fecha'    => $resultado->fecha,
                    'receptor' => $resultado->receptor,
                ]),
            ]);

            return $resultado;
        } catch (TesseractOcrException $e) {
            $tiempoTotal = round((microtime(true) - $tiempoInicio) * 1000);
            Log::error('TesseractOCR: error de Tesseract', [
                'error'     => $e->getMessage(),
                'class'     => get_class($e),
                'hash'      => $hashSha256,
                'tiempo_ms' => $tiempoTotal,
            ]);
            return null;
        } catch (\Throwable $e) {
            $tiempoTotal = round((microtime(true) - $tiempoInicio) * 1000);
            Log::error('TesseractOCR: excepción inesperada', [
                'error'   => $e->getMessage(),
                'class'   => get_class($e),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'hash'    => $hashSha256,
                'tiempo_ms' => $tiempoTotal,
            ]);
            return null;
        } finally {
            unset($imageContent);
            if ($tempPath !== null && $tempPath !== $fullPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    public function testConnection(): string
    {
        try {
            $tesseract = new TesseractOCR();
            $version = $tesseract->version();
            $langs = $tesseract->availableLanguages();
            return 'Tesseract ' . $version . ' (' . implode(', ', $langs) . ')';
        } catch (\Throwable $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Preprocesa la imagen para mejorar la precisión OCR.
     *
     * Pipeline: original → analizar calidad → [grises + contraste] si es necesario
     *           → redimensionar si es muy pequeña → binarizar si contraste bajo
     *
     * Las imágenes con buena calidad se procesan mínimo para no degradarlas.
     */
    private function preprocesarImagen(string $sourcePath, string $mimeType): ?string
    {
        $src = $this->cargarImagen($sourcePath, $mimeType);
        if ($src === null) return null;

        $originalW = imagesx($src);
        $originalH = imagesy($src);

        Log::debug('Preprocesamiento: imagen cargada', [
            'dimensiones' => $originalW . 'x' . $originalH,
            'mime'        => $mimeType,
        ]);

        $step = 0;
        $modificada = false;

        try {
            // 1. Analizar calidad de la imagen
            $step = 1;
            $stats = $this->analizarHistograma($src, $originalW, $originalH);
            $necesitaMejora = $stats !== null && $stats['desviacion'] < 40;
            $step = 1;
            Log::debug('Preprocesamiento paso 1: análisis de calidad', [
                'media'          => $stats ? round($stats['media'], 1) : 'N/A',
                'desviacion'     => $stats ? round($stats['desviacion'], 1) : 'N/A',
                'necesita_mejora' => $necesitaMejora,
            ]);

            // 2. Escala de grises + contraste solo si la imagen tiene bajo contraste
            if ($necesitaMejora) {
                $step = 2;
                imagefilter($src, IMG_FILTER_GRAYSCALE);
                imagefilter($src, IMG_FILTER_CONTRAST, -25);
                $modificada = true;
                Log::debug('Preprocesamiento paso 2: grises + contraste aplicado');
            } else {
                Log::debug('Preprocesamiento paso 2: omitido (contraste suficiente)');
            }

            // 3. Redimensionar si es muy pequeña (ancho < 800px)
            $step = 3;
            if ($originalW < self::UPSAMPLE_THRESHOLD && $originalH < self::UPSAMPLE_THRESHOLD) {
                $ratio = min(self::MIN_DIMENSION / $originalW, self::MIN_DIMENSION / $originalH);
                $newW = (int) round($originalW * $ratio);
                $newH = (int) round($originalH * $ratio);
                $scaled = imagescale($src, $newW, $newH, IMG_BILINEAR_FIXED);
                if ($scaled !== false) {
                    imagedestroy($src);
                    $src = $scaled;
                    $modificada = true;
                    Log::debug('Preprocesamiento paso 3: redimensionado', [
                        'antes' => $originalW . 'x' . $originalH,
                        'despues' => $newW . 'x' . $newH,
                    ]);
                }
            } else {
                Log::debug('Preprocesamiento paso 3: redimensionado no necesario');
            }

            // 4. Binarización solo si contraste muy bajo
            if ($stats !== null && $stats['desviacion'] < 30) {
                $this->binarizar($src);
                $modificada = true;
                Log::debug('Preprocesamiento paso 4: binarización aplicada');
            } else {
                Log::debug('Preprocesamiento paso 4: binarización omitida (contraste suficiente)');
            }

            // Si no se modificó la imagen, usar original directamente
            if (! $modificada) {
                Log::debug('Preprocesamiento: imagen sin cambios, usando original');
                imagedestroy($src);
                return null;
            }

            // Guardar a archivo temporal
            $tempPath = tempnam(sys_get_temp_dir(), 'ocr_') . '.jpg';
            $saved = imagejpeg($src, $tempPath, 92);
            imagedestroy($src);

            if ($saved === false) {
                Log::error('Preprocesamiento: fallo al guardar imagen temporal');
                return null;
            }

            Log::info('Preprocesamiento: imagen procesada guardada', [
                'temp_path' => $tempPath,
                'tamano'    => filesize($tempPath) . ' bytes',
            ]);

            return $tempPath;
        } catch (\Throwable $e) {
            if (isset($src)) imagedestroy($src);
            Log::error("Preprocesamiento: error en paso {$step}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function cargarImagen(string $path, string $mimeType): \GdImage|null
    {
        return match (true) {
            str_contains($mimeType, 'jpeg') || str_contains($mimeType, 'jpg') => @imagecreatefromjpeg($path),
            str_contains($mimeType, 'png')  => @imagecreatefrompng($path),
            str_contains($mimeType, 'gif')  => @imagecreatefromgif($path),
            str_contains($mimeType, 'webp') => @imagecreatefromwebp($path),
            default => null,
        };
    }

    /**
     * Binarización adaptativa que solo se aplica si la imagen tiene
     * contraste insuficiente (desviación estándar baja).
     * Usa Otsu-like threshold con preasignación de colores para velocidad.
     */
    private function binarizar(\GdImage $img): void
    {
        $w = imagesx($img);
        $h = imagesy($img);

        $stats = $this->analizarHistograma($img, $w, $h);

        if ($stats === null) return;

        // Si el contraste (desviación) es alto, la imagen ya es legible
        if ($stats['desviacion'] > 50) {
            Log::debug('Binarización: omitida (contraste suficiente)', [
                'desviacion' => round($stats['desviacion'], 1),
            ]);
            return;
        }

        Log::debug('Binarización: aplicando umbral', [
            'threshold'   => $stats['threshold'],
            'desviacion'  => round($stats['desviacion'], 1),
            'media'       => round($stats['media'], 1),
        ]);

        $black = imagecolorallocate($img, 0, 0, 0);
        $white = imagecolorallocate($img, 255, 255, 255);
        $threshold = $stats['threshold'];

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $gray = ($rgb >> 16) & 0xFF;
                imagesetpixel($img, $x, $y, $gray > $threshold ? $white : $black);
            }
        }
    }

    /**
     * Analiza el histograma de grises: media y desviación estándar
     * mediante muestreo. Calcula un umbral Otsu aproximado.
     */
    private function analizarHistograma(\GdImage $img, int $w, int $h): ?array
    {
        $total = 0;
        $count = 0;
        $sampleStep = max(1, (int) sqrt(($w * $h) / self::BINARIZE_SAMPLES));
        $muestras = [];

        for ($x = 0; $x < $w; $x += $sampleStep) {
            for ($y = 0; $y < $h; $y += $sampleStep) {
                $rgb = imagecolorat($img, $x, $y);
                $gray = ($rgb >> 16) & 0xFF;
                $total += $gray;
                $muestras[] = $gray;
                $count++;
            }
        }

        if ($count === 0) return null;

        $media = $total / $count;

        $varianza = 0;
        foreach ($muestras as $g) {
            $varianza += ($g - $media) ** 2;
        }
        $desviacion = sqrt($varianza / $count);

        // Otsu approximation: find threshold that maximizes between-class variance
        $threshold = $this->otsuThreshold($muestras, $count, $media);

        return [
            'media'      => $media,
            'desviacion' => $desviacion,
            'threshold'  => $threshold,
        ];
    }

    private function otsuThreshold(array $samples, int $count, float $mean): int
    {
        if ($count < 10) return (int) round($mean);

        $min = min($samples);
        $max = max($samples);
        $range = $max - $min;
        if ($range < 5) return (int) round($mean);

        // Build histogram from samples
        $bins = min(256, $range + 1);
        $step = $range / $bins;
        $hist = array_fill(0, $bins, 0);
        foreach ($samples as $g) {
            $idx = min($bins - 1, (int) (($g - $min) / $step));
            $hist[$idx]++;
        }

        $total = $count;
        $sumTotal = 0;
        for ($i = 0; $i < $bins; $i++) {
            $val = $min + $i * $step;
            $sumTotal += $val * $hist[$i];
        }

        $sumB = 0;
        $wB = 0;
        $maxVariance = 0;
        $bestThreshold = (int) round($mean);

        for ($i = 0; $i < $bins; $i++) {
            $wF = $total - $wB;
            if ($wB > 0 && $wF > 0) {
                $mB = $sumB / $wB;
                $mF = ($sumTotal - $sumB) / $wF;
                $between = $wB * $wF * ($mB - $mF) * ($mB - $mF);
                if ($between > $maxVariance) {
                    $maxVariance = $between;
                    $bestThreshold = (int) round($min + $i * $step);
                }
            }
            $val = $min + $i * $step;
            $wB += $hist[$i];
            $sumB += $val * $hist[$i];
        }

        return $bestThreshold;
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

    private function calcularConfianza(string $text): float
    {
        $lines = explode("\n", $text);
        $nonEmptyLines = array_filter($lines, fn($l) => trim($l) !== '');
        $totalChars = strlen($text);
        $alphaChars = preg_match_all('/[a-zA-Z0-9]/', $text);

        if ($totalChars === 0) {
            return 0.0;
        }

        $ratio = $alphaChars / max($totalChars, 1);

        if ($ratio > 0.9 && count($nonEmptyLines) >= 3) {
            return 0.95;
        }
        if ($ratio > 0.7 && count($nonEmptyLines) >= 2) {
            return 0.85;
        }
        if ($ratio > 0.5) {
            return 0.70;
        }

        return 0.50;
    }

    private function normalizarTexto(string $text): string
    {
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/^ +/m', '', $text);
        $text = preg_replace('/ +$/m', '', $text);

        $replacements = [
            "\r\n" => "\n",
            "\r"   => "\n",
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    private function lineasParaExtraccion(string $fullText): array
    {
        $normalized = $this->normalizarTexto($fullText);

        $normalized = preg_replace(
            '/(?<=[A-Z])I(?=[A-Z])/',
            '1',
            $normalized,
        );

        return array_map('trim', explode("\n", $normalized));
    }

    private function parseResultado(string $fullText, float $confianza, string $hashSha256): ResultadoOcr
    {
        $lines = explode("\n", $fullText);
        $cleanLines = $this->lineasParaExtraccion($fullText);

        $codigo = $this->extraerCodigoOperacion($cleanLines, $lines);
        $monto = $this->extraerMonto($cleanLines, $lines);
        $fecha = $this->extraerFecha($cleanLines, $lines);
        $receptor = $this->extraerReceptor($lines, $cleanLines);
        $hora = $this->extraerHora($cleanLines);
        $banco = $this->extraerBanco($cleanLines, $lines);

        Log::debug('Extracción adicional OCR', array_filter([
            'hora'  => $hora,
            'banco' => $banco,
        ]));

        return new ResultadoOcr(
            codigoOperacion: $codigo,
            monto:           $monto,
            fecha:           $fecha,
            receptor:        $receptor,
            confianzaGlobal: $confianza,
            textoRaw:        $fullText,
            hashSha256:      $hashSha256,
        );
    }

    /**
     * Busca patrón de número de operación resistente a errores de Tesseract:
     *   Nro. de operación, N° operac., operacion, 0peracion, código.
     * Maneja confusión O/0, I/1, acentos faltantes.
     */
    private function extraerCodigoOperacion(array $cleanLines, array $rawLines): ?string
    {
        $normalizedMap = [];
        foreach ($cleanLines as $i => $line) {
            $normalizedMap[] = preg_replace('/[Oo]/', '0', $line);
        }

        $patterns = [
            '/(?:N[DR]0?\.?\s*(?:DE\s*)?|C[0O]DIG0\s*(?:DE\s*)?|N[MU]?[ME]?[R]?[0O]?\s*)[0O]PERACI[0O]N[:\s]*([A-Z0-9]{6,})/u',
            '/C[0O]DIG0\s+([A-Z0-9]{6,})/u',
            '/(?:N[DR]0?\.?|NUM|NUMER0)\s+(?:DE\s+)?[0O]PERACI[0O]N[:\s]*([A-Z0-9]{6,})/u',
        ];

        foreach ($normalizedMap as $line) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line, $m)) {
                    $code = $this->corregirDigitos($m[1]);
                    if (preg_match('/^[A-Z0-9]{6,}$/', $code)) {
                        return $code;
                    }
                }
            }
        }

        foreach ($rawLines as $line) {
            if (preg_match('/\b([A-Z0-9]{6,})\s*$/', $line, $m)) {
                $code = $this->corregirDigitos($m[1]);
                if (preg_match('/^[A-Z0-9]{6,}$/', $code)) {
                    return $code;
                }
            }
        }

        return null;
    }
/**
 * Busca monto resistente a errores de Tesseract.
 *
 * Soporta:
 * - S/1, S/2, S/5, S/10
 * - S/ 1
 * - S1, S2
 * - S/1.00
 * - errores comunes de OCR: O/0, I/1, S/5
 *
 * IMPORTANTE:
 * Esta función NO cambia el monto por sí sola basándose
 * en el monto esperado del pedido. Solo extrae lo que
 * realmente aparece en el comprobante.
 */
private function extraerMonto(array $cleanLines, array $rawLines): ?float
{
    $normalizedMap = [];

    foreach ($cleanLines as $line) {
        $normalizedMap[] = strtr($line, [
            'O' => '0',
            'o' => '0',
        ]);
    }

    /*
     * 1. PRIORIDAD: detectar directamente S/10, S/ 10, S10, etc.
     */
    $moneyPattern = '~^[S5s\$]\s*[/:\|I1l]\s*([0-9]+(?:\.[0-9]{1,2})?)$~u';

    foreach ($normalizedMap as $line) {
        $line = trim($line);

        if (preg_match($moneyPattern, $line, $m)) {
            $val = $this->corregirDigitos($m[1]);

            if (
                is_numeric($val)
                && (float) $val > 0
                && (float) $val < 100000
            ) {
                Log::info('MontoOCR: monto encontrado junto a símbolo monetario', [
                    'linea'   => $line,
                    'captura' => $m[1],
                    'monto'   => (float) $val,
                ]);

                return (float) $val;
            }
        }
    }

    /*
     * 2. Si Tesseract separa:
     *
     * S/
     * 10
     */
    $lineCount = count($normalizedMap);

    for ($i = 0; $i < $lineCount; $i++) {
        $current = trim($normalizedMap[$i]);

        if (preg_match('/^[S5s\$]\s*[/\:\|I1l]?\s*$/u', $current)) {

            if (isset($normalizedMap[$i + 1])) {
                $next = trim($normalizedMap[$i + 1]);

                if (preg_match('/^([0-9]+(?:\.[0-9]{1,2})?)$/u', $next, $m)) {
                    $val = $this->corregirDigitos($m[1]);

                    if (
                        is_numeric($val)
                        && (float) $val > 0
                        && (float) $val < 100000
                    ) {
                        Log::info('MontoOCR: monto encontrado en línea siguiente', [
                            'linea_simbolo' => $current,
                            'linea_monto'   => $next,
                            'monto'         => (float) $val,
                        ]);

                        return (float) $val;
                    }
                }
            }
        }
    }

    /*
     * 3. Buscar formatos como:
     *
     * MONTO: S/10
     * PAGO: S/10
     * TOTAL: S/10
     */
$labelPattern =
    '/(?:M0NT0|M0NTO|MONTO|PAG0|PAGO|T0TAL|TOTAL|IMPORTE)'
    . '[:\s]*'
    . '[S5s$]\s*[\/:\|I1l]?\s*'
    . '([0-9]+(?:\.[0-9]{1,2})?)/u';
    foreach ($normalizedMap as $line) {
        if (preg_match($labelPattern, $line, $m)) {
            $val = $this->corregirDigitos($m[1]);

            if (
                is_numeric($val)
                && (float) $val > 0
                && (float) $val < 100000
            ) {
                Log::info('MontoOCR: monto encontrado por etiqueta', [
                    'linea'   => $line,
                    'captura' => $m[1],
                    'monto'   => (float) $val,
                ]);

                return (float) $val;
            }
        }
    }

    /*
     * 4. Si no encuentra S/ + número, NO inventar monto.
     */
    Log::warning('MontoOCR: no se encontró un monto monetario confiable', [
        'cleanLines' => $normalizedMap,
        'rawLines'   => $rawLines,
    ]);

    return null;
}
    private function extraerFecha(array $cleanLines, array $rawLines): ?string
    {
        $normalizedMap = [];
        foreach ($cleanLines as $i => $line) {
            $normalizedMap[] = preg_replace('/[Oo]/', '0', $line);
        }

        $yearPattern = '(?:20[0-9]{2}|[0-9]{2})';

        $founded = null;

        foreach ($normalizedMap as $line) {
            if (preg_match("/\b(\d{2})\s*[\/\-\\.]\s*(\d{2})\s*[\/\-\\.]\s*({$yearPattern})\b/u", $line, $m)) {
                $dia = $this->corregirDigitos($m[1]);
                $mes = $this->corregirDigitos($m[2]);
                $ano = $this->corregirDigitos($m[3]);
                if (checkdate((int) $mes, (int) $dia, (int) $ano)) {
                    return sprintf('%02d/%02d/%s', $dia, $mes, strlen($ano) === 2 ? '20' . $ano : $ano);
                }
            }

            if (preg_match("/\b(\d{1,2})\s+([a-zA-Z]{3,5})\.?\s*({$yearPattern})\b/u", $line, $m)) {
                $dia = (int) $this->corregirDigitos($m[1]);
                $mesStr = $this->corregirMes($m[2]);
                $ano = $this->corregirDigitos($m[3]);
                $meses = $this->mesesMap();
                if (isset($meses[$mesStr])) {
                    $founded = sprintf('%02d/%s/%s', $dia, $meses[$mesStr], strlen($ano) === 2 ? '20' . $ano : $ano);
                    break;
                }
            }

            if (preg_match("/\b(\d{2})([a-zA-Z]{3,5})\.?\s*({$yearPattern})\b/u", $line, $m)) {
                $dia = (int) $this->corregirDigitos($m[1]);
                $mesStr = $this->corregirMes($m[2]);
                $ano = $this->corregirDigitos($m[3]);
                $meses = $this->mesesMap();
                if (isset($meses[$mesStr])) {
                    $founded = sprintf('%02d/%s/%s', $dia, $meses[$mesStr], strlen($ano) === 2 ? '20' . $ano : $ano);
                    break;
                }
            }

            if (preg_match("/\b(\d{1})[\s]*(\d{2})([a-zA-Z]{3,5})\.?\s*({$yearPattern})\b/u", $line, $m)) {
                $dia = (int) $this->corregirDigitos($m[2]);
                $mesStr = $this->corregirMes($m[3]);
                $ano = $this->corregirDigitos($m[4]);
                $meses = $this->mesesMap();
                if (isset($meses[$mesStr])) {
                    $founded = sprintf('%02d/%s/%s', $dia, $meses[$mesStr], strlen($ano) === 2 ? '20' . $ano : $ano);
                    break;
                }
            }

            if (preg_match("/\b({$yearPattern})\s*[\/\-\\.]\s*(\d{2})\s*[\/\-\\.]\s*(\d{2})\b/", $line, $m)) {
                $ano = $this->corregirDigitos($m[1]);
                $mes = $this->corregirDigitos($m[2]);
                $dia = $this->corregirDigitos($m[3]);
                if (checkdate((int) $mes, (int) $dia, (int) $ano)) {
                    return sprintf('%02d/%02d/%s', $dia, $mes, $ano);
                }
            }
        }

        return $founded;
    }

    /**
     * Busca hora en formato hh:mm AM/PM o hh:mm (24h).
     */
    private function extraerHora(array $cleanLines): ?string
    {
        foreach ($cleanLines as $line) {
            if (preg_match('/\b(\d{1,2}):(\d{2})\s*(a\.?\s*m\.?|p\.?\s*m\.?)/iu', $line, $m)) {
                $h = (int) $m[1];
                $min = $m[2];
                $ampm = strtolower(str_replace('.', '', $m[3]));
                if (str_starts_with($ampm, 'p') && $h < 12) $h += 12;
                if (str_starts_with($ampm, 'a') && $h === 12) $h = 0;
                return sprintf('%02d:%s', $h, $min);
            }
            if (preg_match('/\b(\d{1,2}):(\d{2})\b/', $line, $m)) {
                $h = (int) $m[1];
                if ($h >= 0 && $h <= 23) {
                    return sprintf('%02d:%s', $h, $m[2]);
                }
            }
        }
        return null;
    }

    /**
     * Detecta nombre de banco o billetera digital.
     */
    private function extraerBanco(array $cleanLines, array $rawLines): ?string
    {
        $bancos = [
            'YAPE' => 'Yape',
            'BCP'  => 'BCP',
            'BBVA' => 'BBVA',
            'INTERBANK' => 'Interbank',
            'SCOTIABANK' => 'Scotiabank',
            'BANCO DE LA NACION' => 'Banco de la Nación',
            'BANCO NACION' => 'Banco de la Nación',
            'BANBIF' => 'BanBif',
            'MI BANCO' => 'Mi Banco',
            'CAJA' => 'Caja',
            'PAGOEFECTIVO' => 'PagoEfectivo',
            'BIM' => 'BIM',
            'TUNKI' => 'Tunki',
            'LUKITA' => 'Lukita',
        ];

        foreach ($cleanLines as $line) {
            $upper = strtoupper($line);
            foreach ($bancos as $key => $nombre) {
                if (str_contains($upper, $key)) {
                    return $nombre;
                }
            }
        }

        return null;
    }

    /**
     * Busca nombre del receptor o titular de la cuenta.
     *   Pagado a, Receptor, Para, o línea antes de la fecha.
     */
    private function extraerReceptor(array $rawLines, array $cleanLines): ?string
    {
        foreach ($rawLines as $line) {
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

        foreach ($rawLines as $i => $line) {
            if (trim($line) === '' || preg_match('/^\d/', $line)) continue;
            $nextIdx = $i + 1;
            while ($nextIdx < count($rawLines) && trim($rawLines[$nextIdx]) === '') $nextIdx++;
            if ($nextIdx < count($rawLines) && preg_match('/\d{1,2}\s*[a-záéíóú]{3,4}\./iu', $rawLines[$nextIdx])) {
                return trim(preg_replace('/\*+$/', '', $line));
            }
        }

        foreach ($cleanLines as $i => $line) {
            if (trim($line) === '' || preg_match('/^\d/', $line)) continue;
            if (preg_match('/COMPARTIR|YAPEASTE|SEGURIDAD|TRANSACCION|[<>\[\]]/', $line)) continue;
            $nextIdx = $i + 1;
            while ($nextIdx < count($cleanLines) && trim($cleanLines[$nextIdx]) === '') $nextIdx++;
            if ($nextIdx < count($cleanLines) && preg_match('/\d{1,2}:\d{2}/', $cleanLines[$nextIdx])) {
                return trim(preg_replace('/\*+$/', '', $line));
            }
        }

        return null;
    }

    private function corregirDigitos(string $val): string
    {
        $val = strtr($val, [
            'O' => '0', 'o' => '0',
        ]);
        $val = preg_replace('/(?<=[0-9])I(?=[0-9])/', '1', $val);
        return $val;
    }

    private function corregirMes(string $raw): string
    {
        $raw = strtolower(trim($raw, " \t\n\r\0\x0B."));
        $raw = preg_replace('/^[0-9\s]+/', '', $raw);
        $raw = strtr($raw, [
            '0' => 'o', '1' => 'i', '3' => 'e', '4' => 'a', '5' => 's', '6' => 'g', '8' => 'b',
        ]);
        return substr($raw, 0, 5);
    }

    private function mesesMap(): array
    {
        return [
            'ene' => '01', 'feb' => '02', 'mar' => '03', 'abr' => '04',
            'may' => '05', 'jun' => '06', 'jul' => '07', 'ago' => '08',
            'sep' => '09', 'oct' => '10', 'nov' => '11', 'dic' => '12',
            'enero' => '01', 'febrero' => '02', 'marzo' => '03', 'abril' => '04',
            'mayo' => '05', 'junio' => '06', 'julio' => '07', 'agosto' => '08',
            'septiembre' => '09', 'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12',
        ];
    }
}
