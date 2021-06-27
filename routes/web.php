<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DenominationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');

Route::middleware('auth:web', 'verified', 'user_is_active')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/order/datatable', [OrderController::class, 'datatable'])->name('order.datatable');
    Route::resource('/order', OrderController::class)->except('show');

    Route::post('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
    Route::resource('/user', UserController::class)->except('show');

    Route::post('/branch/datatable', [BranchController::class, 'datatable'])->name('branch.datatable');
    Route::resource('/branch', BranchController::class)->except('show');

    Route::post('/customer/datatable', [CustomerController::class, 'datatable'])->name('customer.datatable');
    Route::resource('/customer', CustomerController::class)->except('show');

    Route::post('/denomination/datatable', [DenominationController::class, 'datatable'])->name('denomination.datatable');
    Route::resource('/denomination', DenominationController::class)->except('show');

    Route::post('/role/datatable', [RoleController::class, 'datatable'])->name('role.datatable');
    Route::resource('/role', RoleController::class)->except('show');

    Route::post('/configuration/datatable', [ConfigurationController::class, 'datatable'])->name('configuration.datatable');
    Route::resource('/configuration', ConfigurationController::class)->except('show');
});

require __DIR__.'/auth.php';
