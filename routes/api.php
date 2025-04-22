<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DamagedProductController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


//Dashboard
Route::get('/dashboard-data', [DashboardController::class, 'index']);

//Login
Route::post('/login', [AuthController::class, 'login']);

//Inventory
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/{id}/receive', [ProductController::class, 'receive']);
Route::post('/products/{id}/deduct', [ProductController::class, 'deduct']);
Route::post('/products/{id}/hide', [ProductController::class, 'hideProduct']);
Route::post('/products/{id}/unhide', [ProductController::class, 'unhideProduct']);

//Damaged Products
Route::get('/damaged-products', [DamagedProductController::class, 'index']);
Route::post('/damaged-products', [DamagedProductController::class, 'store']);

//PurchasedList
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
});


Route::get('/test-cors', function () {
    return response()->json(['message' => 'CORS is working!']);
});
