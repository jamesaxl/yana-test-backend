<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PassPortController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProviderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RoleController;

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

Route::post('/login', [PassPortController::class, 'login']);

Route::middleware('auth:api')->group(function () {

//    Route::middleware('is.enabled')->group(function () {
        Route::prefix('user')->group(function () {
//            Route::middleware('is.admin')->group(function () {
                Route::post('/store', [UserController::class, 'store']);
                Route::get('/all', [UserController::class, 'index']);
                Route::get('/show/{id}', [UserController::class, 'show']);
                Route::post('/update/{id}', [UserController::class, 'update']);
                Route::delete('/delete/{id}', [UserController::class, 'delete']);
//            });
            Route::post('/logout', [PassPortController::class, 'logout']);
        });

    Route::prefix('role')->group(function () {
        Route::get('/all', [RoleController::class, 'index']);
    });

        Route::prefix('category')->group(function () {
            Route::post('/store', [CategoryController::class, 'store']);
            Route::get('/all', [CategoryController::class, 'index']);
            Route::get('/show/{id}', [CategoryController::class, 'show']);
            Route::post('/update/{id}', [CategoryController::class, 'update']);
            Route::delete('/delete/{id}', [CategoryController::class, 'delete']);
        });

        Route::prefix('provider')->group(function () {
            Route::post('/store', [ProviderController::class, 'store']);
            Route::get('/all', [ProviderController::class, 'index']);
            Route::get('/show/{id}', [ProviderController::class, 'show']);
            Route::post('/update/{id}', [ProviderController::class, 'update']);
            Route::delete('/delete/{id}', [ProviderController::class, 'delete']);
        });

        Route::prefix('product')->group(function () {
            Route::post('/store', [ProductController::class, 'store']);
            Route::get('/all', [ProductController::class, 'index']);
            Route::get('/show/{id}', [ProductController::class, 'show']);
            Route::post('/update/{id}', [ProductController::class, 'update']);
            Route::delete('/delete/{id}', [ProductController::class, 'delete']);
        });
        
//    });
});
