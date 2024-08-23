<?php

use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::resource('positions', PositionController::class);

    Route::prefix('leave-request')->group(function () {
        Route::get('', [LeaveRequestController::class, 'index'])->name('leave.index');
        Route::get('create', [LeaveRequestController::class, 'create'])->name('leave.create');
        Route::post('', [LeaveRequestController::class, 'store'])->name('leave.store');
        Route::get('{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave.edit');
        Route::put('{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave.update');
        Route::delete('{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave.destroy');
    });
});
