<?php

use Whoops\Run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KanbanController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\NotificationController;

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
        Route::get('/projects/{id}', [ProjectController::class, 'show']);

        Route::get('kanban-board', [KanbanController::class, 'index']);
        Route::post('kanban-task', [KanbanController::class, 'storeTask']);
        Route::patch('/kanban-task', [KanbanController::class, 'updateTaskStatus']);
        Route::put('/kanban-task/{id}', [KanbanController::class, 'updateTask']);
        Route::delete('/kanban-task/{id}', [KanbanController::class, 'destroyTask']);

        route::get('user-attendance', [AttendanceController::class, 'userIndex']);
        route::get('company-location', [AttendanceController::class, 'companyLocation']);
        route::post('user-attendance', [AttendanceController::class, 'userAttendance']);

        Route::get('notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
        Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
        Route::patch('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read');


        route::get('leave-request', [LeaveRequestController::class, 'index']);
        route::post('leave-request', [LeaveRequestController::class, 'store']);

        Route::post('comment', [CommentController::class, 'store'])->name('store.comment');
        Route::post('comments/{comment}/reply', [CommentController::class, 'reply'])->name('reply.comment');
        Route::put('comment/{comment}', [CommentController::class, 'update'])->name('update.comment');
        Route::delete('comment/{comment}', [CommentController::class, 'destroy'])->name('destroy.comment');
    });
});
