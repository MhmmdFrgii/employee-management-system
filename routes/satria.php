<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// project
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('projectAssignments', ProjectAssignmentController::class);
});