<?php

use App\Http\Controllers\KanbanBoardController;
use App\Http\Controllers\KanbanTasksController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('kanbanboard', KanbanBoardController::class);
    Route::resource('kanbantasks', KanbanTasksController::class);
});
