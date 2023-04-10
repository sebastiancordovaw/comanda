<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Redirect::route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::post('/get-products', [ProductController::class,'getProducts'])->name('get-products');
    Route::post('/get-tables', [TableController::class,'getTables'])->name('get-tables');
    Route::post('/set-order-detail', [OrderController::class,'setOrderDetail'])->name('set-order-detail');
    Route::post('/get-order-detail', [OrderController::class,'getOrderDetail'])->name('get-order-detail');
    Route::post('/delete-order-table', [OrderController::class,'deleteOrderTable'])->name('delete-order-table');
    Route::post('/apply-discount', [OrderController::class,'applyDiscount'])->name('apply-discount');
    Route::post('/get-discount', [OrderController::class,'getDiscount'])->name('get-discount');
    Route::post('/delete-discount', [OrderController::class,'deleteDiscount'])->name('delete-discount');
    Route::post('/close-order', [OrderController::class,'closeOrder'])->name('close-order');
    Route::post('/change-table', [TableController::class,'changeTable'])->name('change-table');
    Route::post('/close-order-check', [OrderController::class,'CloseOrderCheck'])->name('close-order-check');
    
    
    

});
