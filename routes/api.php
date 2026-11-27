<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClimaController;
use App\Http\Controllers\Api\HerramientaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/clima', ClimaController::class);
        Route::get('/herramientas', [HerramientaController::class, 'index']);
        Route::get('/herramientas/{herramienta}', [HerramientaController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/herramientas', [HerramientaController::class, 'store']);
        Route::patch('/herramientas/{herramienta}/disponibilidad', [HerramientaController::class, 'disponibilidad']);
    });
});
