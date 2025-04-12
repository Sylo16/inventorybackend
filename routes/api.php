<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;


Route::get('/dashboard-data', [DashboardController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);


