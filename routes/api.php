<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
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