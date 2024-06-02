<?php

use App\Http\Controllers\CheckSessionController;
use App\Http\Controllers\EditUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

use App\Http\Controllers\MessageController;





use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/users/register', RegisterController::class)->name('register');
Route::post('/users/login', LoginController::class)->name('login');
Route::post('/users/logout', LogoutController::class)->name('logout');
Route::post('/users/checkSession', CheckSessionController::class)->name('check-session');
Route::any('/users/edit', EditUserController::class)->name('edit-user');
Route::post('/send-message', [MessageController::class, 'sendMessage']);
Route::get('/messages/{user1}/{user2}',[MessageController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/users', [UserController::class, 'listAll'])->name('list.users');
    Route::get('/user/{id}', [UserController::class, 'getById'])->name('show.user');
    Route::post('/add/users', [UserController::class, 'AddUser'])->name('add.user');
    Route::post('/edit/user/{id}', [UserController::class, 'EditUser'])->name('edit.user');
    Route::post('/delete/user/{id}', [UserController::class, 'destroy'])->name('delete.user');
    
    Route::get('/products', [ProductController::class, 'listAll'])->name('list.products');
    Route::get('/product/{id}', [ProductController::class, 'getById'])->name('show.product');
    Route::post('/add/products', [ProductController::class, 'store']);
    Route::post('/delete/product/{id}', [ProductController::class, 'destroy'])->name('delete.product');
    Route::post('/edit/product/{id}', [ProductController::class, 'update'])->name('edit.product');

    Route::get('/customers', [CustomersController::class, 'listAll'])->name('list.customers');
    Route::get('/customer/{id}', [CustomersController::class, 'getById'])->name('show.customer');
    Route::post('/add/customers', [CustomersController::class, 'AddUser'])->name('add.customer');
    Route::post('/edit/customer/{id}', [CustomersController::class, 'EditUser'])->name('edit.customer');
    Route::post('/delete/customer/{id}', [CustomersController::class, 'destroy'])->name('delete.customer');

    Route::post('/add/sales', [SalesController::class, 'store']);
    Route::get('/sales', [SalesController::class, 'fetchSales']);
    Route::get('/sales-data', [SalesController::class, 'getDashboardData']);

    Route::post('/add/purchases', [PurchaseController::class, 'store']);
    Route::get('/purchases', [PurchaseController::class, 'index']);

    // Route::resource('conversations', 'ConversationController');

});
