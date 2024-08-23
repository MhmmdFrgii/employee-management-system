<?php

use App\Http\Controllers\KanbanBoardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('kanbanboard', KanbanBoardController::class);
});
