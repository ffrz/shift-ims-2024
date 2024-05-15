<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SysEventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\OnlyAdmin;
use App\Http\Middleware\OnlyGuest;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/admin', '/admin/dashboard');

Route::middleware([OnlyGuest::class])->group(function () {
    Route::get('admin/login', [AuthController::class, 'login'])->name('login');
    Route::post('admin/login', [AuthController::class, 'authenticate']);
});

Route::middleware([Authenticate::class, OnlyAdmin::class])->prefix('admin')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::controller(ServiceOrderController::class)->prefix('service-order')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('duplicate/{id}', 'duplicate');
        Route::get('delete/{id}', 'delete');
        Route::get('detail/{id}', 'detail');
        Route::post('action/{id}', 'action');
        Route::get('restore/{id}', 'restore');
    });

    Route::controller(SettingsController::class)->prefix('settings')->group(function () {
        Route::get('', 'edit');
        Route::post('save', 'save');
    });

    Route::controller(UserGroupController::class)->prefix('user-groups')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
    });

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::match(['get', 'post'], 'delete/{id}', 'delete');
        Route::match(['get', 'post'], 'profile', 'profile');
    });

    Route::controller(SysEventController::class)->prefix('sys-events')->group(function () {
        Route::get('', 'index');
        Route::get('show/{id}', 'show');
        Route::post('delete', 'delete');
    });
});
