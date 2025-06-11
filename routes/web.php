<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnrollmentSyncController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/monnify/webhook', [PaymentWebhookController::class, 'handleWebhook']);

Route::post('/update-bvn-enrollment-status', [EnrollmentSyncController::class, 'updateStatus']);

Route::group(['as' => 'auth.', 'prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');

    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');

    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// User Routes
Route::middleware(['auth', 'user.active'])->group(function () {
    // User dashboard
    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/verify-user', [VerificationController::class, 'createAccounts'])->name('verify-user');

        Route::middleware(['user.active', 'user.is_kyced'])->group(function () {
            Route::get('/verify-nin', [VerificationController::class, 'ninVerify'])->name('verify-nin');
            Route::get('/verify-nin-phone', [VerificationController::class, 'phoneVerify'])->name('verify-nin-phone');

            Route::get('/verify-bvn', [VerificationController::class, 'bvnVerify'])->name('verify-bvn');

            Route::get('/nin-personalize', [VerificationController::class, 'ninPersonalize'])->name('personalize-nin');
            Route::get('/nin-personalize-auto/{id}', [VerificationController::class, 'ninPersonalize'])->name('personalize-nin-auto');
            Route::get('/ipe', [VerificationController::class, 'showIpe'])->name('ipe');
            Route::get('/bvn-enrollment', [EnrollmentController::class, 'bvnEnrollment'])->name('bvn-enrollment');
            Route::get('/verify-demo', [VerificationController::class, 'demoVerify'])->name('verify-demo');

            //Ipe request
            Route::post('/ipe-request', [VerificationController::class, 'ipeRequest'])->name('ipe-request');
            Route::get('/ipeStatus/{id}', [VerificationController::class, 'ipeRequestStatus'])->name('ipeStatus');

            //NIN Validation
            Route::get('/nin-validation', [VerificationController::class, 'showNinValidation'])->name('nin-validation');
            Route::post('nin-validation-request', [VerificationController::class, 'ninValidation'])->name('nin-validation-request');

            //NIN Validation
            Route::get('/bvn-phone-search', [VerificationController::class, 'bvnPhoneSearch'])->name('bvn-phone-search');
            Route::post('bvn-phone-search', [VerificationController::class, 'bvnPhoneRequest'])->name('bvn-phone-request');


            //Enrollment-----------------------------------------------------------------------------------------------------
            Route::post('/bvn-enrollment', [EnrollmentController::class, 'enrollBVN'])->name('enroll-bvn');
            //Wallet
            Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
            Route::get('claim-bonus/{id}', [WalletController::class, 'claimBonus'])->name('claim-bonus');

            //Transactions -----------------------------------------------------------------------------------------------------
            Route::get('/receipt/{referenceId}', [TransactionController::class, 'reciept'])->name('reciept');

            //Verification-----------------------------------------------------------------------------------------------------
            //NIN
            Route::post('/nin-retrieve', [VerificationController::class, 'ninRetrieve'])->name('ninRetrieve');
            Route::post('/nin-demo-retrieve', [VerificationController::class, 'ninDemoRetrieve'])->name('nin-demo-Retrieve');
            Route::post('/nin-phone-retrieve', [VerificationController::class, 'ninPhoneRetrieve'])->name('ninPhoneRetrieve');
            Route::post('/nin-track-retrieve', [VerificationController::class, 'ninTrackRetrieve'])->name('ninTrackRetrieve');
            //BVN
            Route::post('/bvn-retrieve', [VerificationController::class, 'bvnRetrieve'])->name('bvnRetrieve');

            //PDF Downloads -----------------------------------------------------------------------------------------------------
            Route::get('/standardBVN/{id}', [VerificationController::class, 'standardBVN'])->name("standardBVN");
            Route::get('/premiumBVN/{id}', [VerificationController::class, 'premiumBVN'])->name("premiumBVN");
            Route::get('/plasticBVN/{id}', [VerificationController::class, 'plasticBVN'])->name("plasticBVN");

            Route::get('/regularSlip/{id}', [VerificationController::class, 'regularSlip'])->name("regularSlip");
            Route::get('/standardSlip/{id}', [VerificationController::class, 'standardSlip'])->name("standardSlip");
            Route::get('/premiumSlip/{id}', [VerificationController::class, 'premiumSlip'])->name("premiumSlip");
            Route::get('/basicSlip/{id}', [VerificationController::class, 'basicSlip'])->name("basicSlip");
            //Whatsapp API Support--------------------------------------------------------------------------
            Route::get('/support', function () {
                // // $phoneNumber = env('phoneNumber');
                // // $message = urlencode(env('message'));
                // $url = env('API_URL') . "{$phoneNumber}&text={$message}";
                // return redirect($url);
                //New Implementation
                return redirect()->away(env('API_URL'));
            })->name('support');
        });
    });
    // Logout Route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'user.active', 'user.admin']], function () {
    // Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

    // // Site Settings
    // Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    // Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

    // Services
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/edit/{id}', [ServicesController::class, 'edit'])->name('services.edit');
    Route::put('/services/update/{id}', [ServicesController::class, 'update'])->name('services.update');

    Route::get('/receipt/{referenceId}', [TransactionController::class, 'recieptAdmin'])->name('reciept');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('user.activate');

    Route::get('/nin-services', [ServicesController::class, 'ninServicesList'])->name('nin.services.list');
    Route::post('/requests/{id}/{type}/update-status', [ServicesController::class, 'updateRequestStatus'])->name('update-request-status');
    Route::get('/view-request/{id}/{type}/edit', [ServicesController::class, 'showRequests'])->name('view-request');

    Route::get('/bvn-services', [ServicesController::class, 'bvnServicesList'])->name('bvn.services.list');
    Route::post('/requests/{id}/{type}/update-bvn-status', [ServicesController::class, 'updateBvnRequestStatus'])->name('bvn-update-request-status');
    Route::get('/view-bvn-request/{id}/{type}/edit', [ServicesController::class, 'showBvnRequests'])->name('bvn-view-request');
});
