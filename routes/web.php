<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\UserManagement\UserManagementController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Security\SecurityController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Leave\LeaveController;
use App\Http\Controllers\IncidentReport\IncidentReportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\JobPosting\JobPostingController;

// Root route
Route::get('/', function () {
    return redirect()->route('login.index');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', [LoginController::class, 'index'])->name('login.index');
        Route::get('/register', [LoginController::class, 'register'])->name('login.register');
        Route::post('/store', [LoginController::class, 'store'])->name('login.store');
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
    });
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    // User Management
    Route::prefix('user-management')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
        Route::get('/json', [UserManagementController::class, 'getUsers'])->name('user-management.json');
        Route::get('/json-approval', [UserManagementController::class, 'getUsersForApproval'])->name('user-management.json.approval');
        Route::get('/pending-approval', [UserManagementController::class, 'forApprovalIndex'])->name('user-management.pending-approval');
        Route::get('/users/approve/{id}', [UserManagementController::class, 'approveUser'])->name('user-management.approve');
        Route::get('/faculty-member-creation', [UserManagementController::class, 'facultyMembersCreationIndex'])->name('user-management.faculty-creation.index');
        Route::post('/faculty-member-creation/store', [UserManagementController::class, 'storeFacultyMember'])->name('user-management.faculty-creation.store');
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
        Route::put('/{id}/update', [UserManagementController::class, 'update'])->name('user-management.update');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });

    // Employee Management
    Route::prefix('employee')->group(function () {
        Route::get('/list', [EmployeeController::class, 'index'])->name('employee.list');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
        Route::get('/archived', [EmployeeController::class, 'archived'])->name('employee.archived');
        Route::put('/restore/{id}', [EmployeeController::class, 'restore'])->name('employee.restore');
    });

    // Security
    Route::prefix('security')->group(function () {
        Route::get('/list', [SecurityController::class, 'index'])->name('security.list');
        Route::get('/deployments', [SecurityController::class, 'deployments'])->name('security.deployments');
        Route::get('/deploy/{id}', [SecurityController::class, 'showDeployForm'])->name('security.deploy.form');
        Route::post('/deploy/{id}', [SecurityController::class, 'storeDeployment'])->name('security.deploy.store');
        Route::put('/make-inactive/{id}', [SecurityController::class, 'makeInactive'])->name('security.makeInactive');
    });

    // Applications & Job Postings
    Route::prefix('applications')->group(function () {
        Route::get('/list', [ApplicationController::class, 'index'])->name('applications.list');
        Route::get('/rejected', [ApplicationController::class, 'rejected'])->name('applications.rejected');
        Route::get('/shortlist', [ApplicationController::class, 'shortlist'])->name('applications.shortlist');

        // Job postings
        Route::prefix('job-postings')->group(function () {
            Route::get('/list', [JobPostingController::class, 'list'])->name('job_postings.list');
            Route::get('/create', [JobPostingController::class, 'create'])->name('job_postings.create');
            Route::post('/store', [JobPostingController::class, 'store'])->name('job_postings.store');
        });
    });

    // Leaves
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/list', [LeaveController::class, 'index'])->name('list');
        Route::get('/pending', [LeaveController::class, 'pending'])->name('pending');
        Route::get('/accepted', [LeaveController::class, 'accepted'])->name('accepted');
        Route::get('/rejected', [LeaveController::class, 'rejected'])->name('rejected');
        Route::get('/processed', [LeaveController::class, 'processed'])->name('processed');
        Route::get('/request', [LeaveController::class, 'create'])->name('request');
        Route::post('/request/store', [LeaveController::class, 'store'])->name('request.store');
        Route::match(['put','post'],'/approve/{id}', [LeaveController::class, 'approve'])->name('approve');
        Route::match(['put','post'],'/reject/{id}', [LeaveController::class, 'reject'])->name('reject');
    });

   // Incident Reports
Route::prefix('incident-reports')->group(function () {

    // Main page for IR (so route('incident-reports.index') works)
    Route::get('/', [IncidentReportController::class, 'create'])->name('incident-reports.index');

    // Submit IR
    Route::get('/submit', [IncidentReportController::class, 'create'])->name('incident-reports.submit');
    Route::post('/store', [IncidentReportController::class, 'store'])->name('incident-reports.store');

    // IR Logs
    Route::get('/logs', [IncidentReportController::class, 'logs'])->name('incident-reports.logs');
});

    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('/add', [AdminController::class, 'add'])->name('admin.add');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    });

    // Force password change
    Route::post('/force-change-password', [LoginController::class, 'forceChangePassword'])->name('password.forceChange');
});
