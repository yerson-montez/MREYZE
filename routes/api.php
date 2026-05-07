<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ProductoController;

Route::get('/test', function () {
    return response()->json([
        'status' => true,
        'message' => 'API funcionando correctamente'
    ]);
});

Route::post('/contacto', [ContactoController::class, 'enviar']);