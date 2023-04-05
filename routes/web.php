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

});
