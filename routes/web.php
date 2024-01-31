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

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // profile
    Route::get('/dashboard/users-profile',[ProfileController::class, 'index']);
    Route::get('/dashboard/users-profile/confirm',[ProfileController::class, 'confirmPass']);
    Route::post('/dashboard/users-profile/newPass',[ProfileController::class, 'newPass']);
    Route::post('/dashboard/users-profile/update/{id}',[ProfileController::class, 'update']);

//  sale
Route::get('/dashboard/sale', [SaleController::class, 'index']);
Route::get('/search', [SaleController::class, 'search']);
Route::get('/result', [SaleController::class, 'result']);
Route::post('/dashboard/sale' ,[SaleController::class, 'store']);
Route::post('/dashboard/sale/print' ,[SaleController::class, 'print']);


// customer pages
    Route::get('/dashboard/customer', [CustomerController::class , 'index']);
    Route::get('/dashboard/customer-add', [CustomerController::class, 'create']);
    Route::post('/dashboard/customer-add', [CustomerController::class, 'store']);
    Route::get('/dashboard/customer/edit/{code_customer}', [CustomerController::class, 'edit']);
    Route::put('/dashboard/customer-edit/{code_customer}', [CustomerController::class, 'update']);
    Route::get('/dashboard/customer-delete/{code_customer}', [CustomerController::class, 'destroy']);
    // Route::resource('customer', CustomerContrsoller::class);

// customer-general product
    Route::get('/dashboard/general', [GeneralController::class, 'index']);
    Route::get('/dashboard/general-add',[GeneralController::class, 'create']);
    Route::post('/dashboard/general-add', [GeneralController::class, 'store']);
    Route::get('/dashboard/general-edit/{code_customer}', [GeneralController::class, 'edit']);
    Route::put('/dashboard/general-edit/{code_customer}', [GeneralController::class, 'update']);
    Route::get('/dashboard/general-delete/{code_customer}', [GeneralController::class, 'destroy']);

// product pages
    Route::get('/dashboard/product', [ProductController::class, 'index']);
    Route::get('/dashboard/product-add',[ProductController::class, 'create']);
    Route::post('/dashboard/product-add', [ProductController::class , 'store']);
    Route::get('/dashboard/product-edit/{slug}', [ProductController::class, 'edit']);
    Route::put('/dashboard/product-edit/{slug}', [ProductController::class, 'update']);
    Route::get('/dashboard/product-delete/{slug}', [ProductController::class, 'destroy']);

// product-in
    Route::get('/dashboard/productIn', [ProductInController::class, 'index']);
    Route::post('/dashboard/productIn-add', [ProductInController::class, 'store']);
    Route::get('/dashboard/historyProduct/{slug}', [ProductInController::class, 'show']);
    Route::get('/dashboard/productIn-delete/{slug}', [ProductInController::class, 'destroy']);


    // product-out
    Route::get('/dashboard/productOut', [ProductOutController::class, 'index']);
    Route::get('/dashboard/productOut/detail', [ProductOutController::class, 'detail'] );
    Route::delete('/dashboard/productOut-delete/{sale_id}', [ProductOutController::class, 'destroy'] );
    // Route::get('/dashboard/productOut-delete/{sale_id}', [ProductOutController::class, 'destroy'] );

    
// Category pages
    Route::get('/dashboard/category', [CategoryController::class, 'index']);
    Route::post('/dashboard/category-add', [CategoryController::class, 'store']);
    Route::put('/dashboard/category-edit/{slug}', [CategoryController::class, 'update']);
    Route::get('/dashboard/category-delete/{slug}', [CategoryController::class, 'destroy']);

// unit pages
    Route::get('/dashboard/unit',[UnitController::class,'index']);
    Route::post('/dashboard/unit-add', [UnitController::class, 'store']);
    Route::put('/dashboard/unit-edit/{slug}', [UnitController::class , 'update']);
    Route::get('/dashboard/unit-delete/{slug}', [UnitController::class , 'destroy']);

    // laporan keuangan
    Route::get('/dashboard/report', [ReportController::class, 'index']);
    Route::get('/dashboard/report-cek', [ReportController::class,'cekReport']);
    Route::get('/dashboard/report-print', [ReportController::class,'print']);

    // laporan barang
    Route::get('/dashboard/report/unit', [ReportUnitController::class, 'index']);


    // user
    Route::get('/dashboard/user', [UserController::class, 'index']);
    Route::post('/dashboard/user-add', [UserController::class, 'store']);
    Route::put('/dashboard/user-edit/{id}', [UserController::class, 'update']);
    Route::delete('/dashboard/user-delete/{id}', [UserController::class, 'destroy']);
    Route::put('/dashboard/user-pass/{id}', [UserController::class, 'passUpdate']);
    
});