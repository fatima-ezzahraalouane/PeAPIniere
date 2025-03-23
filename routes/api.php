<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatisticsController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

Route::get('/plants', [PlantController::class, 'index']);
Route::get('/plants/{slug}', [PlantController::class, 'show']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{slug}', [CategoryController::class, 'update']);
    Route::delete('/categories/{slug}', [CategoryController::class, 'destroy']);

    Route::post('/plants', [PlantController::class, 'store']);
    Route::put('/plants/{slug}', [PlantController::class, 'update']);
    Route::delete('/plants/{slug}', [PlantController::class, 'destroy']);
});

Route::middleware(['auth:api', 'role:client'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'myOrders']);
    Route::delete('/orders/{id}', [OrderController::class, 'cancel']);
});

Route::middleware(['auth:api', 'role:employee'])->group(function () {
    Route::get('/admin/orders', [OrderController::class, 'allOrders']);
    Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);
});

Route::middleware(['auth:api', 'role:admin'])->prefix('admin/statistics')->group(function () {
    Route::get('/total-orders', [StatisticsController::class, 'totalOrders']);
    Route::get('/total-revenue', [StatisticsController::class, 'totalRevenue']);
    Route::get('/top-plants', [StatisticsController::class, 'topPlants']);
    Route::get('/sales-by-category', [StatisticsController::class, 'salesByCategory']);
});



































































//    🌱 Admin uniquement
//   Route::middleware('role:admin')->group(function () {
//     Route::post('/plants', [PlantController::class, 'store']);
//     Route::post('/categories', [CategoryController::class, 'store']);
// });

//  📦 Employé uniquement
// Route::middleware('role:employee')->group(function () {
//     Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
// });

//  🛒 Client uniquement
// Route::middleware('role:client')->group(function () {
//     Route::post('/orders', [OrderController::class, 'store']);
//     Route::get('/my-orders', [OrderController::class, 'myOrders']);
// });