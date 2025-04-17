<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DamagedProductController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard-data', [DashboardController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/products', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/{id}/receive', [ProductController::class, 'receive']);
Route::post('/products/{id}/deduct', [ProductController::class, 'deduct']);


Route::get('/damaged-products', [DamagedProductController::class, 'index']);
Route::post('/damaged-products', [DamagedProductController::class, 'store']);