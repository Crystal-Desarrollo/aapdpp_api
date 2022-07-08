<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LinkController;
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

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::apiResource("/links", LinkController::class);
    Route::apiResource("/articles", ArticleController::class);

    Route::post("/register", [AuthController::class, 'register'])->name("auth.register");
    Route::post("/logout", [AuthController::class, 'logout'])->name("auth.logout");
    Route::get('/user', [AuthController::class, 'me'])->name('auth.me');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'delete'])->name('users.delete');
});

Route::post("/login", [AuthController::class, 'login'])->name("auth.login")->middleware('guest');
Route::get("/articles", [ArticleController::class, 'index']);
Route::get("/links", [LinkController::class, 'index']);
