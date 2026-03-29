<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/kiosk'));
Route::get('/kiosk', [PageController::class, 'kiosk']);
Route::get('/kitchen', [PageController::class, 'kitchen']);
Route::get('/analytics', [PageController::class, 'analytics']);
Route::get('/admin/menu', [PageController::class, 'adminMenu']);
