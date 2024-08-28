<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalarieController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/applicants', [UserController::class, 'index']);
Route::get('/applicant/detail/{id}', [UserController::class, 'detail'])->name('applicant.detail');

Route::middleware('auth')->group(function () {
    Route::resource('department', DepartmentController::class);
    Route::resource('salaries', SalarieController::class);
});
