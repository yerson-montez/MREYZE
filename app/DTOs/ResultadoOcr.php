<?php

namespace App\DTOs;

class ResultadoOcr
{
    public function __construct(
        public readonly ?string $codigoOperacion,
        public readonly ?float  $monto,
        public readonly ?string $fecha,
        public readonly ?string $receptor,
        public readonly float   $confianzaGlobal,
        public readonly string  $textoRaw,
        public readonly string  $hashSha256,
    ) {}

    public function toArray(): array
    {
        return [
            'codigo_operacion' => $this->codigoOperacion,
            'monto'            => $this->monto,
            'fecha'            => $this->fecha,
            'receptor'         => $this->receptor,
            'confianza'        => $this->confianzaGlobal,
            'texto_raw'        => $this->textoRaw,
            'hash_sha256'      => $this->hashSha256,
        ];
    }
}
