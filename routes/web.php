<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockUpdateController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserActivityController;
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

    Route::controller(ReportController::class)->prefix('report')->group(function () {
        Route::get('', 'index');
        Route::get('inventory-stock', 'inventoryStock');
    });

    Route::controller(ServiceOrderController::class)->prefix('service-order')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('duplicate/{id}', 'duplicate');
        Route::get('delete/{id}', 'delete');
        Route::get('detail/{id}', 'detail');
        Route::post('action/{id}', 'action');
        Route::get('restore/{id}', 'restore');
        Route::get('print/{id}', 'print');
    });

    Route::controller(SalesOrderController::class)->prefix('sales-order')->group(function () {
        Route::get('', 'index');
        Route::get('create', 'create');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
        Route::post('save-detail/{id}', 'saveDetail');
    });

    Route::controller(SupplierController::class)->prefix('supplier')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
    });

    Route::controller(CustomerController::class)->prefix('customer')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
    });

    Route::controller(ProductCategoryController::class)->prefix('product-category')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
    });

    Route::controller(ProductController::class)->prefix('product')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
        Route::get('restore/{id}', 'restore');
        Route::get('duplicate/{id}', 'duplicate');
    });

    Route::controller(SettingsController::class)->prefix('settings')->group(function () {
        Route::get('', 'edit');
        Route::post('save', 'save');
    });

    Route::controller(UserGroupController::class)->prefix('user-group')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
    });

    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::match(['get', 'post'], 'delete/{id}', 'delete');
        Route::match(['get', 'post'], 'profile', 'profile');
    });

    Route::controller(UserActivityController::class)->prefix('user-activity')->group(function () {
        Route::get('', 'index');
        Route::get('show/{id}', 'show');
        Route::post('delete', 'delete');
    });

    Route::controller(StockUpdateController::class)->prefix('stock-update-history')->group(function () {
        Route::get('', 'index');
        Route::get('detail/{id}', 'detail');
    });

    Route::controller(StockAdjustmentController::class)->prefix('stock-adjustment')->group(function () {
        Route::get('', 'index');
        Route::match(['get', 'post'], 'create', 'create');
        Route::get('detail/{id}', 'detail');
        Route::match(['get', 'post'], 'edit/{id}', 'edit');
        Route::get('delete/{id}', 'delete');
    });

    Route::get('refresh-csrf', function () {
        return csrf_token();
    });
});
