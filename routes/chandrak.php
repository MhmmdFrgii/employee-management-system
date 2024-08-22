<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('attendance', AttendanceController::class);
});

Route::get('/mark-absentees', [AttendanceController::class, 'markAbsentees']);
