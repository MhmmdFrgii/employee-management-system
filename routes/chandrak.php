<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeDetailsController;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('attendance', AttendanceController::class);
    Route::resource('employee', EmployeeDetailsController::class);
    Route::get('/mark-absentees', [AttendanceController::class, 'markAbsentees']);
});
route::get('/employees/search', [EmployeeDetailsController::class, 'search'])->name('employee.search');
