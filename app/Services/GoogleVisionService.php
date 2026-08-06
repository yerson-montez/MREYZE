<?php

namespace App\Services;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleVisionService
{
    public function leerTexto($rutaImagen)
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . config('google.credentials'));

        $client = new ImageAnnotatorClient();

        $image = file_get_contents($rutaImagen);

        $response = $client->textDetection($image);

        $client->close();

        $texts = $response->getTextAnnotations();

        if (count($texts) > 0) {
            return $texts[0]->getDescription();
        }

        return null;
    }
}