<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RequestCodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/request-code', [RequestCodeController::class, 'requestCode'])->middleware('throttle:5,3')->name('request-code');
Route::post('/register', [RegisterController::class, 'register'])->name('user.register');
Route::post('/webhook/update-status', [WebhookController::class, 'updateStatus'])->name('update-status');
Route::get('/users', [UserController::class, 'getUsers'])->name('users');
