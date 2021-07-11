<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DenominationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
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

Route::middleware('auth:web', 'verified', 'user_is_active')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('/order')->name('order.')->group(function () {
        Route::post('/datatable', [OrderController::class, 'datatable'])->name('datatable');
        Route::post('/{configuration}/datatable-row-child', [OrderController::class, 'datatableRowChild'])->name('datatable-row-child');
    });

    Route::prefix('/user')->name('user.')->group(function () {
        Route::post('/datatable', [UserController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [UserController::class, 'destroyMultiple'])->name('destroy-multiple');
    });

    Route::prefix('/branch')->name('branch.')->group(function () {
        Route::post('/datatable', [BranchController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [BranchController::class, 'destroyMultiple'])->name('destroy-multiple');
    });

    Route::prefix('/customer')->name('customer.')->group(function () {
        Route::post('/datatable', [CustomerController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [CustomerController::class, 'destroyMultiple'])->name('destroy-multiple');
        Route::delete('/{customer}/identitycard_image', [CustomerController::class, 'destroyIdentitycardImage'])->name('destroy-identitycard_image');
    });

    Route::prefix('/denomination')->name('denomination.')->group(function () {
        Route::post('/datatable', [DenominationController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [DenominationController::class, 'destroyMultiple'])->name('destroy-multiple');
        Route::delete('/{denomination}/image', [DenominationController::class, 'destroyImage'])->name('destroy-image');
    });

    Route::prefix('/role')->name('role.')->middleware('role:admin')->group(function () {
        Route::post('/datatable', [RoleController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [RoleController::class, 'destroyMultiple'])->name('destroy-multiple');
    });

    Route::prefix('/configuration')->name('configuration.')->group(function () {
        Route::post('/datatable', [ConfigurationController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [ConfigurationController::class, 'destroyMultiple'])->middleware('role:admin')->name('destroy-multiple');
    });

    Route::resource('/order', OrderController::class)->except('show');
    Route::resource('/user', UserController::class)->except('show');
    Route::resource('/branch', BranchController::class)->except('show');
    Route::resource('/customer', CustomerController::class)->except('show');
    Route::resource('/denomination', DenominationController::class)->except('show');
    Route::resource('/role', RoleController::class, ['middleware' => 'role:admin'])->except('show');
    Route::resource('/configuration', ConfigurationController::class)->except('show');
});

require __DIR__.'/auth.php';
