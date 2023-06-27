<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\LogController;
use App\Models\Tag;
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

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Route::get('/tags', [TagController::class, 'index']);
// Route::post('/tags', [TagController::class, 'store']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('tags', TagController::class);
    Route::get('/logout',[AuthController::class, 'logout']);
    Route::get('/count', [TagController::class, 'countTags']);
    Route::get('/tags/search/{identifier}', [TagController::class, 'search']);
    Route::get('/users',[AuthController::class, 'user']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::put('/users/{id}', [AuthController::class, 'adminUpdate']);
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/log', [LogController::class, 'index']);
    Route::delete('/log', [LogController::class, 'destroy']);
});
// Route::middleware('auth:sanctum')->get('/tags/search/{identifier}', [TagController::class, 'search']);
