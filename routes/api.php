<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtAuth;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

Route::get('/me', [AuthController::class, 'me'])->name('me')->middleware(JwtAuth::class);
