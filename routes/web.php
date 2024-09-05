<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeDetailController;
use App\Http\Controllers\KanbanBoardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\KanbanTaskController;
use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\SalaryController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');


Route::get('confirmation', function () {
    return view('confirmation');
})->name('confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:manager', 'auth', 'check_location'])->group(function () {

        Route::prefix('manager')->group(function () {
            // route attendence

            Route::get('/mark-absentees', [AttendanceController::class, 'markAbsentees']);

            route::post('/kanban-tasks/update-order', [KanbanTaskController::class, 'updateOrder'])->name('kanban-tasks.update-order');
            Route::post('/kanban-tasks/update-status', [KanbanTaskController::class, 'updateStatus'])->name('kanban-tasks.update-status');

            Route::get('dashboard', [DashboardController::class, 'index'])->name('manager.dashboard');

            Route::resource('project-assignments', ProjectAssignmentController::class);
            Route::resource('departments', DepartmentController::class);
            Route::resource('salaries', SalaryController::class);
            Route::resource('positions', PositionController::class);
            Route::resource('attendance', AttendanceController::class);

            Route::get('/get-employees/{department_id}', [EmployeeDetailController::class, 'get_employees'])->name('employee.get');
            Route::resource('employees', EmployeeDetailController::class);

            Route::patch('/projects/{id}/complete', [ProjectController::class, 'mark_completed'])->name('projects.complete');
            Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
            Route::resource('projects', ProjectController::class);

            Route::get('/applicants/detail/{id}', [UserController::class, 'detail'])->name('applicants.detail');
            Route::patch('applicants/reject/{applicant}', [UserController::class, 'reject'])->name('applicants.reject');
            Route::resource('applicants', UserController::class);

            Route::patch('/company/{company}', [CompanyController::class, 'reset_code'])->name('companies.reset');
        });
    });

    Route::middleware(['role:manager', 'check_exists_location'])->group(function () {
        Route::get('company-location', [RegisteredUserController::class, 'setup_location'])
            ->name('company.location.setup');

        Route::patch('company-location', [RegisteredUserController::class, 'store_location'])
            ->name('company.location.store');
    });

    Route::middleware('role:employee')->group(function () {
        Route::prefix('employee')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'userDashboard'])->name('employee.dashboard');
            Route::get('/my-projects', [ProjectController::class, 'myProjects'])->name('project.user');

            Route::get('/attendance', [AttendanceController::class, 'user_index'])->name('attendance.user');
            Route::post('/attendance-mark', [AttendanceController::class, 'user_attendance'])->name('attendance.mark');
            Route::get('/employee-list', [EmployeeDetailController::class, 'user_index'])->name('employee.user');
        });
    });

    Route::resource('notifications', NotificationController::class);
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::resource('kanban-boards', KanbanBoardController::class);
    Route::resource('kanban-tasks', KanbanTaskController::class);

    // Route untuk approve leave request
    Route::put('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
});

require __DIR__ . '/auth.php';
