<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;

// ==========================================
// 🔐 RUTAS DE SEGURIDAD (Protegidas contra Fuerza Bruta)
// ==========================================
// Permitimos solo 5 peticiones por minuto para evitar que adivinen contraseñas
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/setup', [AuthController::class, 'setup']); // Crea el primer admin
    Route::post('/login', [AuthController::class, 'login']); // Inicia sesión
    Route::post('/recover', [AuthController::class, 'recover']); // Recuperar clave
});

// ==========================================
// 🛍️️ RUTAS PÚBLICAS (Con protección Anti-DoS)
// ==========================================
// Permitimos 30 peticiones por minuto para la navegación de la tienda
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});

// ==========================================
// 🛡️ RUTAS PROTEGIDAS (Solo el Administrador con Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Gestión de Productos
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Gestión de Pedidos
    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // Para ver los datos del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
