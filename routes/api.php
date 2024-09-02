<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthApiController::class, 'login']);

Route::get('attendance', [AttendanceApiController::class, 'index']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
