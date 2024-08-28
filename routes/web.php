<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SalarieController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyprojectController;
use App\Http\Controllers\UserAbsenController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KanbanBoardController;
use App\Http\Controllers\KanbanTasksController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmployeeDetailsController;
use App\Http\Controllers\ProjectAssignmentController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Project
    Route::resource('projects', ProjectController::class);
    Route::patch('/projects/{id}/complete', [ProjectController::class, 'markAsCompleted'])->name('projects.complete');

    // Route Project Assignments
    Route::resource('projectAssignments', ProjectAssignmentController::class);

    // Route notification
    Route::resource('notifikasi', NotificationController::class);

    // Route Department
    Route::resource('department', DepartmentController::class);

    //Route salaries
    Route::resource('salaries', SalarieController::class);

    // Absensi user
    Route::resource('absensi', UserAbsenController::class);

    // Route applicants 
    Route::get('/applicants', [UserController::class, 'index'])->name('applicant.index');
    Route::get('/applicant/detail/{id}', [UserController::class, 'detail'])->name('applicant.detail');
    Route::patch('/applicant/{applicant}', [UserController::class, 'update'])->name('applicant.update');

    // Route positions
    Route::resource('positions', PositionController::class);

    Route::prefix('leave-request')->group(function () {
        Route::get('', [LeaveRequestController::class, 'index'])->name('leave.index');
        Route::get('create', [LeaveRequestController::class, 'create'])->name('leave.create');
        Route::post('', [LeaveRequestController::class, 'store'])->name('leave.store');
        Route::get('{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave.edit');
        Route::put('{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave.update');
        Route::delete('{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave.destroy');
    });

    Route::resource('kanbanboard', KanbanBoardController::class);

    Route::resource('kanbantasks', KanbanTasksController::class);

    // route employee
    Route::resource('employee', EmployeeDetailsController::class);
    Route::get('/userKaryawan', [EmployeeDetailsController::class, 'userKaryawan']);

    // route attendence
    Route::resource('attendance', AttendanceController::class);
    Route::get('/mark-absentees', [AttendanceController::class, 'markAbsentees']);

    Route::resource('myproject', MyprojectController::class);

    Route::prefix('employee')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'userDashboard'])->name('employee.dashboard');
    });
});

require __DIR__ . '/auth.php';
