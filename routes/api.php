<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DamagedProductController;
use App\Http\Controllers\CustomerController;



//Dashboard
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);



//Login
Route::post('/login', [AuthController::class, 'login']);

//Inventory
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::put('/products/{id}/receive', [ProductController::class, 'receive']);
Route::put('products/{productName}/deduct', [ProductController::class, 'deduct']);
Route::post('/products/{id}/hide', [ProductController::class, 'hideProduct']);
Route::post('/products/{id}/unhide', [ProductController::class, 'unhideProduct']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::apiResource('products', ProductController::class);


//Damaged Products
Route::get('/damaged-products', [DamagedProductController::class, 'index']);
Route::post('/damaged-products', [DamagedProductController::class, 'store']);
Route::get('/damaged-products/stats', [DamagedProductController::class, 'stats']);

//PurchasedList
Route::prefix('customers')->group(function () {
Route::get('/', [CustomerController::class, 'index']);
Route::post('/', [CustomerController::class, 'store']);

});



//Test
Route::get('/test-cors', function () {
    return response()->json(['message' => 'CORS is working!']);
});
