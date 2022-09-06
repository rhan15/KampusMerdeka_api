<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('user')->group(function () {
    Route::get('index', [UserController::class, 'index']);
    
});

// Route::prefix('product')->group(function () {
//     Route::get('index', [ProductController::class, 'index']);
    
// });

// Route::prefix('auth')->group(function () {
//     Route::post('register', [AuthController::class, 'register']);
//     Route::post('login', [AuthController::class, 'login']);

// });



Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    
    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('/profile')->group(function () {
            Route::get('/', 'profile');
            Route::post('/', 'update');
        });

        Route::post('/logout', 'logout');
    });

});

Route::prefix('article')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/articles', [ArticleController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/articles/{id}', [ArticleController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->middleware('auth:sanctum');
    
    
    
});