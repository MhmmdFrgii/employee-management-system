<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalarieController;
use Illuminate\Support\Facades\Route;

Route::resource('department', DepartmentController::class);
Route::resource('salaries', SalarieController::class);



