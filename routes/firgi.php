<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeDetailsController;
use App\Http\Controllers\KanbanBoardController;
use App\Http\Controllers\KanbanTasksController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('kanbanboard', KanbanBoardController::class);
    Route::resource('kanbantasks', KanbanTasksController::class);

    Route::patch('/projects/{id}/complete', [ProjectController::class, 'markAsCompleted'])->name('projects.complete');

    Route::get('/userKaryawan', [EmployeeDetailsController::class, 'userKaryawan']);

});
