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
use App\Http\Controllers\Applicant\ApplicantCredentialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shift\ShiftController;
use App\Http\Controllers\Attendance\AttendanceController;

// Root redirect to login
Route::get('/', function () {
    return redirect()->route('login.index');
});

// ----------------------
// 🧩 Guest Routes
// ----------------------
Route::middleware('guest')->prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login.index');
    Route::get('/register', [LoginController::class, 'register'])->name('login.register');
    Route::post('/store', [LoginController::class, 'store'])->name('login.store');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

// Email verification route (accessible to guests)
Route::get('/email/verify/{id}/{token}', [LoginController::class, 'verifyEmail'])->name('email.verify');

// ----------------------
// 🔒 Logout
// ----------------------
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ----------------------
// 🧭 Authenticated Routes
// ----------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    // ----------------------
    // 👥 User Management
    // ----------------------
    Route::prefix('user-management')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
        Route::get('/json', [UserManagementController::class, 'getUsers'])->name('user-management.json');
        Route::get('/json-non-employees', [UserManagementController::class, 'getNonEmployees'])->name('user-management.json.non-employees');
        Route::post('/deactivate/{id}', [UserManagementController::class, 'deactivateUser'])->name('user-management.deactivate');
        Route::post('/reset-password/{id}', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
        Route::get('/json-approval', [UserManagementController::class, 'getUsersForApproval'])->name('user-management.json.approval');
        Route::get('/pending-approval', [UserManagementController::class, 'forApprovalIndex'])->name('user-management.pending-approval');
        Route::get('/users/approve/{id}', [UserManagementController::class, 'approveUser'])->name('user-management.approve');
        Route::get('/faculty-member-creation', [UserManagementController::class, 'facultyMembersCreationIndex'])->name('user-management.faculty-creation.index');
        Route::post('/faculty-member-creation/store', [UserManagementController::class, 'storeFacultyMember'])->name('user-management.faculty-creation.store');
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
        Route::put('/{id}/update', [UserManagementController::class, 'update'])->name('user-management.update');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });

    // ----------------------
    // 👔 Employee Management
    // ----------------------
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

    // ----------------------
    // 🛡️ Security Management
    // ----------------------
    Route::prefix('security')->group(function () {
        Route::get('/list', [SecurityController::class, 'index'])->name('security.list');
        Route::get('/deployments', [SecurityController::class, 'deployments'])->name('security.deployments');
        Route::get('/deploy/{id}', [SecurityController::class, 'showDeployForm'])->name('security.deploy.form');
        Route::post('/deploy/{id}', [SecurityController::class, 'storeDeployment'])->name('security.deploy.store');
        Route::put('/make-inactive/{id}', [SecurityController::class, 'makeInactive'])->name('security.makeInactive');
    });

    // ----------------------
    // 📅 Guard Scheduling
    // ----------------------
    Route::prefix('guard-scheduling')->group(function () {
        Route::get('/assign', [SecurityController::class, 'assignSchedule'])->name('guard-scheduling.assign');
        Route::get('/assign/{guard}', [SecurityController::class, 'showGuardSchedule'])->name('guard-scheduling.assign.guard');
        Route::post('/assign/{guard}/store', [SecurityController::class, 'storeSchedule'])->name('guard-scheduling.assign.store');
        Route::get('/deploy', [SecurityController::class, 'deploy'])->name('guard-scheduling.deploy');
        Route::get('/list', [SecurityController::class, 'guardList'])->name('guard-scheduling.list');
    });

    // ----------------------
    // 📄 Applications & Job Postings
    // ----------------------
    Route::prefix('applications')->group(function () {
        Route::get('/list', [ApplicationController::class, 'index'])->name('applications.list');
        Route::get('/rejected', [ApplicationController::class, 'rejected'])->name('applications.rejected');
        Route::get('/shortlist', [ApplicationController::class, 'shortlist'])->name('applications.shortlist');

        // 🧾 Job Postings
        Route::prefix('job-postings')->group(function () {
            Route::get('/list', [JobPostingController::class, 'list'])->name('job_postings.list');
            Route::get('/create', [JobPostingController::class, 'create'])->name('job_postings.create');
            Route::post('/store', [JobPostingController::class, 'store'])->name('job_postings.store');
            Route::get('/show/{id}', [JobPostingController::class, 'show'])->name('job_postings.show');
            Route::post('/toggle-status/{id}', [JobPostingController::class, 'toggleStatus'])->name('job_postings.toggle-status');
            Route::get('/applications/{id}', [JobPostingController::class, 'showApplications'])->name('job_postings.applications');
            Route::post('/applications/reject/{id}', [JobPostingController::class, 'rejectApplication'])->name('job_postings.applications.reject');
            Route::post('/applications/shortlist/{id}', [JobPostingController::class, 'shortlistApplication'])->name('job_postings.applications.shortlist');
            Route::get('/applicant-credentials/{applicationId}', [JobPostingController::class, 'showApplicantCredentials'])->name('job_postings.applicant-credentials');
        });
    });

    // ----------------------
    // 🌴 Leave Management
    // ----------------------
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/list', [LeaveController::class, 'index'])->name('list');
        Route::get('/pending', [LeaveController::class, 'pending'])->name('pending');
        Route::get('/accepted', [LeaveController::class, 'accepted'])->name('accepted');
        Route::get('/rejected', [LeaveController::class, 'rejected'])->name('rejected');
        Route::get('/processed', [LeaveController::class, 'processed'])->name('processed');
        Route::get('/request', [LeaveController::class, 'create'])->name('request');
        Route::post('/request/store', [LeaveController::class, 'store'])->name('request.store');
        Route::match(['put', 'post'], '/approve/{id}', [LeaveController::class, 'approve'])->name('approve');
        Route::match(['put', 'post'], '/reject/{id}', [LeaveController::class, 'reject'])->name('reject');
    });

    // ----------------------
    // ⚠️ Incident Reports
    // ----------------------
    Route::prefix('incident-reports')->group(function () {
        Route::get('/', [IncidentReportController::class, 'create'])->name('incident-reports.index');
        Route::get('/submit', [IncidentReportController::class, 'create'])->name('incident-reports.submit');
        Route::post('/store', [IncidentReportController::class, 'store'])->name('incident-reports.store');
        Route::get('/logs', [IncidentReportController::class, 'logs'])->name('incident-reports.logs');
    });

    // ----------------------
    // 🧑‍💼 Admin Routes
    // ----------------------
    Route::prefix('admin')->group(function () {
        Route::get('/add', [AdminController::class, 'add'])->name('admin.add');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    });

    // ----------------------
    // 🔐 Force Password Change
    // ----------------------
    Route::post('/force-change-password', [LoginController::class, 'forceChangePassword'])->name('password.forceChange');

    // ----------------------
    // 👤 Profile Routes
    // ----------------------
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('employee.update-profile');

    // ----------------------
    // ⏰ Attendance Management
    // ----------------------
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/shift-in', [AttendanceController::class, 'shiftIn'])->name('attendance.shift-in');
        Route::post('/shift-out', [AttendanceController::class, 'shiftOut'])->name('attendance.shift-out');

        // ✅ Added Force Shift Out Route
        Route::post('/force-shift-out', [AttendanceController::class, 'forceShiftOut'])
            ->name('attendance.force-shift-out');
    });

    // ----------------------
    // 🧑‍💻 Applicant Job Portal
    // ----------------------
    Route::prefix('applicant')->group(function () {
        Route::get('/jobs', [JobPostingController::class, 'applicantJobs'])->name('applicant.jobs');
        Route::post('/jobs/apply/{id}', [JobPostingController::class, 'apply'])->name('applicant.jobs.apply');
        Route::get('/applications', [ApplicantCredentialController::class, 'applications'])->name('applicant.applications');
        Route::get('/credentials', [ApplicantCredentialController::class, 'index'])->name('applicant.credentials');
        Route::post('/credentials/store', [ApplicantCredentialController::class, 'store'])->name('applicant.credentials.store');
    });

});
