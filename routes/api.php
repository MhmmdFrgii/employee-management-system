<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KanbanController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Whoops\Run;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:employee')->group(function () {
        Route::get('projects', [ProjectController::class, 'getAllProject']);

        Route::get('kanban-board', [KanbanController::class, 'index']);
        Route::post('kanban-task', [KanbanController::class, 'storeTask']);
        Route::put('/kanban-task/{id}', [KanbanController::class, 'updateTask']);
        Route::delete('/kanban-task/{id}', [KanbanController::class, 'destroyTask']);

        route::get('user-attendance', [AttendanceController::class, 'userIndex']);
        route::post('user-attendance', [AttendanceController::class, 'userAttendance']);

        route::get('leave-request', [LeaveRequestController::class, 'index']);
        route::post('leave-request', [LeaveRequestController::class, 'store']);
    });
});
