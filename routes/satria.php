<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserAbsenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Testing\Fakes\NotificationFake;

Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('projectAssignments', ProjectAssignmentController::class);
    Route::resource('notifikasi', NotificationController::class);

    // Absensi user
    Route::resource('absensi', UserAbsenController::class);

    // Route Dashboard untuk Manager
    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    });

    // Route Dashboard untuk Karyawan
    Route::middleware('role:karyawan')->group(function () {
        Route::get('/karyawan/dashboard', [DashboardController::class, 'karyawanDashboard'])->name('karyawan.dashboard');
    });
});
