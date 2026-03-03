<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\ReportUnitController;
use App\Http\Controllers\RoleCustomerController;
use App\Http\Controllers\SupplierController;

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

// Route::get('/', function () {
//     return view('index');
// });

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // profile
    Route::get('/dashboard/users-profile', [ProfileController::class, 'index']);
    Route::get('/dashboard/users-profile/confirm', [ProfileController::class, 'confirmPass']);
    Route::post('/dashboard/users-profile/newPass', [ProfileController::class, 'newPass']);
    Route::post('/dashboard/users-profile/update/{id}', [ProfileController::class, 'update']);

    //  sale
    Route::get('/dashboard/sale', [SaleController::class, 'index']);
    Route::get('/search', [SaleController::class, 'search'])->name('search.product');
    Route::get('/result', [SaleController::class, 'result']);
    Route::post('/dashboard/sale', [SaleController::class, 'store']);
    Route::post('/dashboard/sale/print', [SaleController::class, 'print']);

    Route::prefix('customer')->group(function () {

        // customer pages
        Route::get('/dashboard/customer', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/dashboard/customer-add', [CustomerController::class, 'create']);
        Route::get('/customer-datatable', [CustomerController::class, 'datatable'])->name('customer.datatable');
        Route::post('/dashboard/customer-add', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/dashboard/customer/edit/{code_customer}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/dashboard/customer-edit/{code_customer}', [CustomerController::class, 'update'])->name('customer.update');
        Route::get('/dashboard/customer-delete/{code_customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
        // Route::resource('customer', CustomerContrsoller::class);
        // customer-general product
        Route::get('/dashboard/general', [GeneralController::class, 'index']);
        Route::get('/dashboard/general-add', [GeneralController::class, 'create']);
        Route::post('/dashboard/general-add', [GeneralController::class, 'store']);
        Route::get('/dashboard/general-edit/{code_customer}', [GeneralController::class, 'edit']);
        Route::put('/dashboard/general-edit/{code_customer}', [GeneralController::class, 'update']);
        Route::get('/dashboard/general-delete/{code_customer}', [GeneralController::class, 'destroy']);
    });


    Route::prefix('dashboard')->group(function () {
        // product pages
        Route::get('product', [ProductController::class, 'index'])->name('product');
        Route::get('product-add', [ProductController::class, 'create'])->name('product.add');
        Route::get('product-datatable', [ProductController::class, 'datatables'])->name('product.datatable');
        Route::post('product-add', [ProductController::class, 'store'])->name('product.store');
        Route::get('product-show/{id}', [ProductController::class, 'show'])->name('product.show');
        Route::get('product-edit/{slug}', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('product-edit/{slug}', [ProductController::class, 'update'])->name('product.update');
        Route::get('product-delete/{slug}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier');
        Route::get('/supplier-datatable', [SupplierController::class, 'datatable'])->name('supplier.datatable');
        Route::get('/supplier-show/{id}', [SupplierController::class, 'show'])->name('supplier.show');
        Route::get('/supplier-add', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/supplier-store', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/supplier-edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::post('/supplier-update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::post('/supplier-destroy/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    });


    Route::prefix('dashboard')->group(function () {

        // product-in
        Route::get('productIn', [ProductInController::class, 'index'])->name('product.in');
        Route::get('productIn-getData', [ProductInController::class, 'getData'])->name('product.in.getData');
        Route::post('productIn-add', [ProductInController::class, 'store'])->name('product.in.store');
        Route::get('historyProduct/{slug}', [ProductInController::class, 'show'])->name('product.in.history');
        Route::get('productIn-delete/{slug}', [ProductInController::class, 'destroy'])->name('product.in.destroy');
        // end product in
    });


    // product-out
    Route::get('/dashboard/productOut', [ProductOutController::class, 'index']);
    Route::get('/dashboard/productOut/detail', [ProductOutController::class, 'detail']);
    Route::delete('/dashboard/productOut-delete/{sale_id}', [ProductOutController::class, 'destroy']);
    // Route::get('/dashboard/productOut-delete/{sale_id}', [ProductOutController::class, 'destroy'] );

    Route::prefix('dashboard/category')->group(function () {

        // Category pages
        Route::get('/dashboard/category', [CategoryController::class, 'index'])->name('category');
        Route::get('/category-datatable', [CategoryController::class, 'datatable'])->name('category.datatable');
        Route::post('/dashboard/category-add', [CategoryController::class, 'store'])->name('category.add');
        Route::get('/dashboard/category-edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/dashboard/category-update/{slug}', [CategoryController::class, 'update'])->name('category.update');
        Route::get('/dashboard/category-delete/{slug}', [CategoryController::class, 'destroy'])->name('category.delete');
    });

    Route::prefix('dashboard/units')->group(function () {

        // unit pages
        Route::get('/unit', [UnitController::class, 'index'])->name('unit');
        Route::post('/unit-add', [UnitController::class, 'store'])->name('unit.add');
        Route::put('/unit-edit/{slug}', [UnitController::class, 'update'])->name('unit.edit');
        Route::get('/unit-delete/{slug}', [UnitController::class, 'destroy'])->name('unit.delete');
    });

    Route::prefix('master-data')->group(function () {

        Route::prefix('role_customer')->group(function () {
            Route::get('/role_customer', [RoleCustomerController::class, 'index'])->name('role_customer.index');
            Route::post('/store', [RoleCustomerController::class, 'store'])->name('role_customer.store');
            Route::get('/edit', [RoleCustomerController::class, 'edit'])->name('role_customer.edit');
            Route::put('/update/{id}', [RoleCustomerController::class, 'update'])->name('role_customer.update');
            Route::get('/destroy/{id}', [RoleCustomerController::class, 'destroy'])->name('role_customer.destroy');
        });
    });

    // laporan keuangan
    Route::get('/dashboard/report', [ReportController::class, 'index']);
    Route::get('/dashboard/report-cek', [ReportController::class, 'cekReport']);
    Route::get('/dashboard/report-print', [ReportController::class, 'print']);

    // laporan barang
    Route::get('/dashboard/report/unit', [ReportUnitController::class, 'index']);


    // user
    Route::get('/dashboard/user', [UserController::class, 'index']);
    Route::post('/dashboard/user-add', [UserController::class, 'store']);
    Route::put('/dashboard/user-edit/{id}', [UserController::class, 'update']);
    Route::delete('/dashboard/user-delete/{id}', [UserController::class, 'destroy']);
    Route::put('/dashboard/user-pass/{id}', [UserController::class, 'passUpdate']);
});
