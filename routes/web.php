<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DenominationController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Report;
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

Route::get('/order/{order}', [OrderController::class, 'show'])->name('admin.order.show');

Route::middleware('auth:web', 'verified', 'user_is_active')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('/order')->name('order.')->group(function () {
        Route::post('/datatable', [OrderController::class, 'datatable'])->name('datatable');
        Route::post('/{order}/datatable-row-child', [OrderController::class, 'datatableRowChild'])->name('datatable-row-child');
        Route::delete('/multiple', [OrderController::class, 'destroyMultiple'])->name('destroy-multiple');

        Route::get('/{order}/status/{enumOrderStatus}/create', [OrderStatusController::class, 'create'])->name('status.create');
        Route::post('/{order}/status/{enumOrderStatus}', [OrderStatusController::class, 'store'])->name('status.store');
        Route::delete('/{order}/status/{status:status}', [OrderStatusController::class, 'destroy'])->name('status.destroy');

        Route::get('/{order}/item/create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/{order}/item', [ItemController::class, 'store'])->name('item.store');
        Route::get('/{order}/item/{item:}/edit', [ItemController::class, 'edit'])->name('item.edit');
        Route::put('/{order}/item/{item:}', [ItemController::class, 'update'])->name('item.update');
        Route::delete('/{order}/item/{item:}', [ItemController::class, 'destroy'])->name('item.destroy');
    });

    Route::prefix('/user')->name('user.')->group(function () {
        Route::post('/datatable', [UserController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [UserController::class, 'destroyMultiple'])->name('destroy-multiple');
        Route::get('/{user}/verify-email-address', [UserController::class, 'verifyEmailAddress'])->name('verify-email-address');
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

    Route::prefix('/role')->name('role.')->group(function () {
        Route::post('/datatable', [RoleController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [RoleController::class, 'destroyMultiple'])->name('destroy-multiple');
    });

    Route::prefix('/configuration')->name('configuration.')->group(function () {
        Route::post('/datatable', [ConfigurationController::class, 'datatable'])->name('datatable');
        Route::delete('/multiple', [ConfigurationController::class, 'destroyMultiple'])->name('destroy-multiple');
    });

    Route::name('report.')->group(function () {
        Route::get('/report-order', [Report\OrderController::class, 'index'])->name('order.index');
        Route::post('/report-order/datatable', [Report\OrderController::class, 'datatable'])->name('order.datatable');
        Route::post('/report-order/export', [Report\OrderController::class, 'export'])->name('order.export');
    });

    Route::resource('/order', OrderController::class)->except('create', 'show', 'edit', 'update');
    Route::resource('/user', UserController::class);
    Route::resource('/branch', BranchController::class);
    Route::resource('/customer', CustomerController::class);
    Route::resource('/denomination', DenominationController::class);
    Route::resource('/role', RoleController::class)->except('show');
    Route::resource('/configuration', ConfigurationController::class)->except('show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
