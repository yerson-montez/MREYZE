<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\ContactoController;

Route::get('/test', [TestController::class, 'index']);

Route::post('/contacto', [ContactoController::class, 'enviar']);