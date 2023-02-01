<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [AuthController::class, 'autorization']);
Route::get('/products', [ProductController::class, 'all']);

Route::group(['middleware' => 'user'], function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/cart', [CartController::class, 'all']);
    Route::post('/add_cart/{id}', [CartController::class, 'add_cart']);
    Route::post('/del_cart/{id}', [CartController::class, 'del_cart']);
    Route::post('/create_order', [OrderController::class, 'create_order']);
    Route::post('/orders', [OrderController::class, 'all']);
});

Route::group(['middleware' => 'admin'], function (){
    Route::post('/add_product', [ProductController::class, 'add_product']);
    Route::post('/delete_product/{id}', [ProductController::class, 'delete_product']);
    Route::post('/edit_product/{id}', [ProductController::class, 'edit_product']);
});
