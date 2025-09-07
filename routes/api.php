<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint de login (público)
Route::post('/login', [AuthController::class, 'login']);

// Endpoints protegidos con token
Route::middleware('auth:sanctum')->group(function () {
    // Información del usuario autenticado
    Route::get('/me', [AuthController::class, 'me']);

    // Listar todos los usuarios
    Route::get('/users', [AuthController::class, 'index']);

    // Ver un usuario específico
    Route::get('/users/{id}', [AuthController::class, 'show']);

    // Productos
    Route::get('/productos', [ProductoController::class, 'index']);
});
