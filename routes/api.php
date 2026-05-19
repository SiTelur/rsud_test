<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);
Route::post('/order', [OrderController::class, 'store'])->middleware('auth:sanctum');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth:sanctum');
Route::delete('/dashboard/cache', function () {
    Cache::forget('dashboard_summary');

    return response()->json([
        'message' => 'Cache dashboard_summary berhasil dihapus.',
    ]);
})->middleware('auth:sanctum');

Route::scopeBindings()->get('/users/{user}/order/{order}', [OrderController::class, 'show'])->middleware('auth:sanctum');
Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('auth:sanctum');
