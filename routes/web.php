<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
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

    Route::post('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
    Route::resource('/user', UserController::class);

    Route::post('/customer/datatable', [CustomerController::class, 'datatable'])->name('customer.datatable');
    Route::resource('/customer', CustomerController::class);
});

require __DIR__.'/auth.php';
