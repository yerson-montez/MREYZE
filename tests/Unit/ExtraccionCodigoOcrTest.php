<?php

namespace Tests\Unit;

use App\Services\TesseractOcrService;
use Tests\TestCase;

class ExtraccionCodigoOcrTest extends TestCase
{
    private function extraerCodigo(array $cleanLines, array $rawLines): ?string
    {
        $service = new TesseractOcrService();

        $method = new \ReflectionMethod(TesseractOcrService::class, 'extraerCodigoOperacion');
        $method->setAccessible(true);

        return $method->invoke($service, $cleanLines, $rawLines);
    }

    public function test_codigo_numerico_en_etiqueta_se_extrae(): void
    {
        $this->assertSame('2790244', $this->extraerCodigo(
            ['Nro. de operación: 2790244'],
            ['Nro. de operación: 2790244'],
        ));
    }

    public function test_codigo_numerico_al_final_de_linea_se_extrae(): void
    {
        $this->assertSame('2790244', $this->extraerCodigo(
            ['PAGO RECIBIDO', '2790244'],
            ['PAGO RECIBIDO', '2790244'],
        ));
    }

    public function test_codigo_con_prefijo_operacion_se_extrae(): void
    {
        $this->assertSame('0P123456', $this->extraerCodigo(
            ['Nro. de operación: OP123456'],
            ['Nro. de operación: OP123456'],
        ));
    }

    public function test_basura_de_ocr_movil_no_se_toma_como_codigo(): void
    {
        $this->assertNull($this->extraerCodigo(
            ['QUNONE', 'NRO DE OPERACION'],
            ['QUNONE', 'NRO DE OPERACION'],
        ));
    }

    public function test_palabra_operacion_en_mayusculas_no_se_toma_como_codigo(): void
    {
        $this->assertNull($this->extraerCodigo(
            ['OPERACION', 'PAGO RECIBIDO'],
            ['OPERACION', 'PAGO RECIBIDO'],
        ));
    }
}
