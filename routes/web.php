<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandscapeController;
use App\Http\Controllers\SwimmingPoolController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\RenovationController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\LaborController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RegisterController; 
use App\Http\Controllers\LoginController;   
use App\Http\Controllers\OTPController; 
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\getUserNotifications;
use App\Http\Controllers\RatesController;









    // Landing page
    Route::get('/', function () {
        return view('welcome'); // Assuming 'welcome' is your landing page view
    })->name('welcome');



    // Authentication routes
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'registerSave'])->name('register.save');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginAction'])->name('login.action');

    Route::post('login-otp', [AuthController::class, 'loginOtp'])->name('login.otp');
    // Routes for authenticated users
    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');


    // Dashboard route
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Archive routes
    Route::get('archive', [ArchiveController::class, 'index'])->name('archive.index');
    Route::put('archive/{id}/restore', [ArchiveController::class, 'restore'])->name('archive.restore');
    Route::delete('archive/{id}', [ArchiveController::class, 'destroy'])->name('archive.destroy');

    // Landscape routes
    Route::get('landscape', [LandscapeController::class, 'index'])->name('landscape');
    Route::get('landscape/create', [LandscapeController::class, 'create'])->name('landscape-services.create');
    Route::post('landscape', [LandscapeController::class, 'store'])->name('landscape-services.store');
    Route::get('landscape/{id}/edit', [LandscapeController::class, 'edit'])->name('landscape-services.edit');
    Route::put('landscape/{id}', [LandscapeController::class, 'update'])->name('landscape-services.update');
    Route::put('landscape/{id}/archive', [LandscapeController::class, 'archive'])->name('landscape-services.archive');

    // Swimming Pool routes
    Route::get('swimmingpool', [SwimmingPoolController::class, 'index'])->name('swimmingpool');
    Route::get('swimmingpool/create', [SwimmingPoolController::class, 'create'])->name('swimmingpool-services.create');
    Route::post('swimmingpool', [SwimmingPoolController::class, 'store'])->name('swimmingpool-services.store');
    Route::get('swimmingpool/{id}/edit', [SwimmingPoolController::class, 'edit'])->name('swimmingpool-services.edit');
    Route::put('swimmingpool/{id}', [SwimmingPoolController::class, 'update'])->name('swimmingpool-services.update');
    Route::put('swimmingpool/{id}/archive', [SwimmingPoolController::class, 'archive'])->name('swimmingpool-services.archive');

    // Renovation routes
    Route::get('renovation', [RenovationController::class, 'index'])->name('renovation');
    Route::get('renovation/create', [RenovationController::class, 'create'])->name('renovation-services.create');
    Route::post('renovation', [RenovationController::class, 'store'])->name('renovation-services.store');
    Route::get('renovation/{id}/edit', [RenovationController::class, 'edit'])->name('renovation-services.edit');
    Route::put('renovation/{id}', [RenovationController::class, 'update'])->name('renovation-services.update');
    Route::put('renovation/{id}/archive', [RenovationController::class, 'archive'])->name('renovation-services.archive');

     // Package routes
     Route::get('package', [PackageController::class, 'index'])->name('package');
     Route::get('package/create', [PackageController::class, 'create'])->name('package-services.create');
     Route::post('package', [PackageController::class, 'store'])->name('package-services.store');
     Route::get('package/{id}/edit', [PackageController::class, 'edit'])->name('package-services.edit');
     Route::put('package/{id}', [PackageController::class, 'update'])->name('package-services.update');
     Route::put('package/{id}/archive', [PackageController::class, 'archive'])->name('package-services.archive');


    Route::get('quotations', [QuotationController::class, 'index'])->name('quotation.index');
    Route::get('quotations/create', [QuotationController::class, 'create'])->name('quotation.create');
    Route::post('quotations', [QuotationController::class, 'store'])->name('quotation.store');
    Route::get('quotations/{id}/edit', [QuotationController::class, 'edit'])->name('quotation.edit');
    Route::put('quotations/{id}', [QuotationController::class, 'update'])->name('quotation.update');
    Route::get('quotations/form', [QuotationController::class, 'form'])->name('quotation.form');
    Route::get('/quotations/view', [QuotationController::class, 'view'])->name('quotation.view');
    Route::get('/designs/{type}', [QuotationController::class, 'getDesigns'])->name('designs.get');
    Route::post('/save-design-id', [DesignController::class, 'saveDesignId'])->name('save.design.id');
    Route::get('/api/cities/{regionId}', [QuotationController::class, 'getCitiesByRegion']);
    Route::post('/calculate-price', [PricingController::class, 'calculatePrice']);
    Route::get('/quotation/{id}', [QuotationController::class, 'details'])->name('quotation.details');


    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/form', [BookingController::class, 'create'])->name('booking.form');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/admin/bookings', [BookingController::class, 'adminBooking'])->name('booking.adminBooking');
    Route::get('/booking/{id}', [BookingController::class, 'adminShow'])->name('booking.adminShow');
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::post('/bookings/{id}/decline', [BookingController::class, 'declineBooking'])->name('bookings.decline');
    Route::get('/bookings/view/{id}', [BookingController::class, 'view'])->name('booking.view');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('booking.update');
    

    // Admin Routes
    Route::get('/admin/projects', [ProjectController::class, 'adminIndex'])->name('project.adminIndex'); // Admin project listing
    Route::get('/admin/projects/create/{booking_id?}', [ProjectController::class, 'create'])->name('projects.create'); // Admin create project
    Route::post('/admin/projects/store', [ProjectController::class, 'store'])->name('projects.store'); // Store new project
    Route::get('/admin/projects/{id}', [ProjectController::class, 'adminShow'])->name('project.adminShow'); // Admin view single project
    Route::patch('/admin/projects/{project}/hold', [ProjectController::class, 'hold'])->name('project.hold'); // Hold a project
    Route::patch('/admin/projects/{id}/activate', [ProjectController::class, 'activate'])->name('project.activate'); // Activate a project
    Route::patch('/projects/{project}/cancel', [ProjectController::class, 'cancel'])->name('project.cancel');
    Route::get('/admin/projects/reports', [ProjectController::class, 'generateReport'])->name('project.reports'); // Generate project report
    Route::get('/project/{id}', [ProjectController::class, 'view'])->name('project.view');



    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::patch('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');


    // User Routes
    Route::get('/projects/{booking_id?}', [ProjectController::class, 'index'])->name('project.index'); // User project listing
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('project.show'); // User view single project
    Route::get('/projects/create/{booking_id?}', [ProjectController::class, 'create'])->name('projects.create'); // Create project for user (if applicable)
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store'); // Store new project for user (if applicable)

    // Additional Routes
    Route::get('/designs/{category}', [ProjectController::class, 'getDesigns']); // Get designs by category
    Route::post('/calculate-cost', [ProjectController::class, 'calculateCost'])->name('calculate.cost'); // Calculate project cost
    Route::get('/services/{category}', [ServiceController::class, 'showByCategory'])->name('services.byCategory'); // Show services by category




    // routes/web.php

    Route::post('/tracking', [ProgressController::class, 'update'])->name('progress.update');
    Route::get('/projects/{project}/tracking', [ProgressController::class, 'show'])->name('tracking.show');
    Route::post('/tracking', [ProgressController::class, 'store'])->name('progress.store');
    Route::get('/progress/{projectId}', [ProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/{projectId}/view', [ProgressController::class, 'view'])->name('progress.view');
    





    Route::get('/projects/{id}/pay', [PaymentController::class, 'create'])->name('pay');
    // routes/web.php
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/admin/payments/{id}', [PaymentController::class, 'adminshow'])->name('admin.payments.show');
    Route::get('/create/{projectId}', [PaymentController::class, 'showPaymentForm'])->name('payment.create');
    Route::post('/projects/{projectId}/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::post('/store/midterm', [PaymentController::class, 'storeMidterm'])->name('payment.store.midterm');
    Route::post('/store/final', [PaymentController::class, 'storeFinal'])->name('payment.store.final');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/admin/payments', [PaymentController::class, 'adminIndex'])->name('admin.payments.index');
    Route::get('/payments/project/{projectId}', [PaymentController::class, 'viewPayments'])->name('payments.view');
    Route::get('/payments/project/{projectId}', [PaymentController::class, 'adminviewPayments'])->name('admin.payments.view');
    Route::post('/admin/payments/{id}/approve', [PaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/admin/payments/{id}/decline', [PaymentController::class, 'decline'])->name('admin.payments.decline');
    Route::get('/admin/payments/{id}/edit', [PaymentController::class, 'edit'])->name('admin.payments.edit');
    Route::post('/admin/payments/{id}/update', [PaymentController::class, 'update'])->name('admin.payments.update');
    Route::get('/payment/{projectId}', [PaymentController::class, 'payment'])->name('payment.payment');


    // Route::get('/reports/projects', [ReportsController::class, 'projects'])->name('reports.projects');
    Route::get('/reports/rates', [ReportsController::class, 'rates'])->name('reports.rates');
    Route::get('/task-log', [TaskLogController::class, 'index'])->name('tasklog.index');
    Route::get('/admin/task-log', [TaskLogController::class, 'adminIndex'])->name('admin.tasklog.index');


    Route::get('/notifications/{id}', [GetUserNotifications::class, 'show'])->name('notifications.show');
    Route::get('/notifications/mark-as-read/{id}', [GetUserNotifications::class, 'markAsRead'])->name('notifications.markAsRead');
    


    Route::get('/admin/rates', [RatesController::class, 'index'])->name('rates.index');
    Route::get('rates/{rate}/edit', [RatesController::class, 'edit'])->name('rates.edit');
    Route::put('rates/{id}', [RatesController::class, 'update'])->name('rates.update');


    


    });

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/login-with-otp-post',[App\Http\Controllers\OTPController::class, 'loginwithotppost'])->name('login.with.otp.post');
    Route::view('/confirm-login-with-otp', 'auth.confirmloginwithotp')->name('confirm.login.with.otp');
    Route::post('/confirm-login-with-otp-post', [App\Http\Controllers\OTPController::class, 'confirmloginwithotppost'])->name('confirm.login.with.otp.post');
    Route::post('/set-otp-null', [YourController::class, 'setOtpNull'])->name('set.otp.null');
    Route::post('/resend-otp', [OTPController::class, 'resendOtp'])->name('resend.otp');

    // Password Reset Routes

    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');


    Auth::routes();
