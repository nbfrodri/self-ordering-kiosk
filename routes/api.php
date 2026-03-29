<?php

use App\Http\Controllers\Api\AdminMenuController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\CocinaController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PedidoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are automatically prefixed with /api and assigned the
| "api" middleware group by the withRouting() call in bootstrap/app.php.
|
| Rate limiting is applied globally via the "api" middleware group
| (default: 60 requests/min). High-traffic endpoints have additional
| stricter throttle limits applied individually below.
|
*/

// Menu — public read-only (cached in controller)
Route::get('/menu', [MenuController::class, 'index']);

// Orders (Kiosk)
Route::post('/pedidos', [PedidoController::class, 'store'])
    ->middleware('throttle:30,1'); // 30 orders per minute per IP
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{orderNumber}/estado', [PedidoController::class, 'show']);

// Kitchen
Route::get('/cocina/pedidos-pendientes', [CocinaController::class, 'pendientes']);
Route::patch('/cocina/pedidos/{orderNumber}/estado', [CocinaController::class, 'actualizarEstado']);

// Analytics
Route::post('/analiticas/eventos', [AnalyticsController::class, 'store'])
    ->middleware('throttle:120,1'); // 120 events per minute per IP (kiosk sends frequent events)
Route::get('/analiticas/resumen', [AnalyticsController::class, 'index']);

// Product image — public, cacheable
Route::get('/products/{id}/image', [AdminMenuController::class, 'imageShow'])
    ->where('id', '[0-9]+');

// Admin menu management
Route::prefix('admin')->group(function () {
    // Categories
    Route::get('/categories',         [AdminMenuController::class, 'categoriesIndex']);
    Route::post('/categories',        [AdminMenuController::class, 'categoriesStore']);
    Route::put('/categories/{id}',    [AdminMenuController::class, 'categoriesUpdate'])->where('id', '[0-9]+');
    Route::delete('/categories/{id}', [AdminMenuController::class, 'categoriesDestroy'])->where('id', '[0-9]+');

    // Products
    Route::get('/products',         [AdminMenuController::class, 'productsIndex']);
    Route::post('/products',        [AdminMenuController::class, 'productsStore']);
    Route::put('/products/{id}',    [AdminMenuController::class, 'productsUpdate'])->where('id', '[0-9]+');
    Route::delete('/products/{id}', [AdminMenuController::class, 'productsDestroy'])->where('id', '[0-9]+');

    // Product images
    Route::post('/products/{id}/image',   [AdminMenuController::class, 'imageStore'])->where('id', '[0-9]+');
    Route::delete('/products/{id}/image', [AdminMenuController::class, 'imageDestroy'])->where('id', '[0-9]+');

    // Customizations
    Route::post('/products/{id}/customizations',  [AdminMenuController::class, 'customizationsStore'])->where('id', '[0-9]+');
    Route::put('/customizations/{id}',            [AdminMenuController::class, 'customizationsUpdate'])->where('id', '[0-9]+');
    Route::delete('/customizations/{id}',         [AdminMenuController::class, 'customizationsDestroy'])->where('id', '[0-9]+');

    // Cache
    Route::post('/cache/clear', [AdminMenuController::class, 'cacheClear']);
});
