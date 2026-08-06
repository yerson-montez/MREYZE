<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleVisionService;

class ProbarVisionOCR extends Command
{
    protected $signature = 'vision:probar {imagen}';

    protected $description = 'Prueba lectura OCR con Google Vision';

    public function handle(GoogleVisionService $vision)
    {
        $ruta = $this->argument('imagen');

        if (!file_exists($ruta)) {
            $this->error("No existe la imagen: $ruta");
            return Command::FAILURE;
        }

        $texto = $vision->leerTexto($ruta);

        $this->info("Texto detectado:");
        $this->line($texto ?? 'No se encontró texto');

        return Command::SUCCESS;
    }
}