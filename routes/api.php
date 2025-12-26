<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TenancyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\SystemConfigController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListingController;

// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes (JWT)
Route::middleware(['auth:api'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    // Resources
    Route::apiResource('users', UserController::class);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('units', UnitController::class);
    Route::apiResource('tenancies', TenancyController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('support-tickets', SupportTicketController::class);
    Route::apiResource('disputes', DisputeController::class);
    Route::apiResource('activity-logs', ActivityLogController::class);
    Route::apiResource('system-configs', SystemConfigController::class);
    Route::apiResource('listings', ListingController::class);

    // List route
    Route::get('lists', [ListController::class, 'index']);
    Route::get('lists/listings', [ListController::class, 'listings']);
    Route::get('lists/users', [ListController::class, 'users']);
    Route::get('lists/landlords', [ListController::class, 'landlords']);
    Route::get('lists/caretakers', [ListController::class, 'caretakers']);
    Route::get('lists/service-providers', [ListController::class, 'serviceProviders']);
    Route::get('lists/tenants', [ListController::class, 'tenants']);
    Route::get('lists/properties', [ListController::class, 'properties']);


    //Delete listing old image when editing
    Route::delete('listings/{listing}/images/{image}', [ListingController::class, 'deleteImage']);

    //Delete ticket old image when editing
    Route::delete('support-tickets/{ticket}/images/{image}', [SupportTicketController::class, 'deleteTicketImage']);

    Route::get('/properties/{id}/units', [PropertyController::class, 'units'])->name('propertyunits');
    Route::get('/landlords/{id}/properties', [PropertyController::class, 'landlordProperties'])->name('landlordproperties');
    Route::get('/properties/{id}/tenants', [PropertyController::class, 'tenants']);

    Route::post('/tenancies/assign', [TenancyController::class, 'assignTenant'])
    ->middleware(['auth:api', 'role:admin,landlord']);

    Route::put('profile/{id}',[UserController::class, 'updateProfile']);
    Route::put('changepassword/{id}',[UserController::class, 'changePassword']);

    Route::post('/invoices/auto-generate', [InvoiceController::class, 'autoGenerate']);

});
