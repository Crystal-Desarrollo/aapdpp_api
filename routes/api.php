<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LinkController;
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

Route::post("/register", [AuthController::class, 'register'])->name("auth.register");
Route::post("/login", [AuthController::class, 'login'])->name("auth.login");

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::apiResource("/links", LinkController::class);
    Route::apiResource("/articles", ArticleController::class);

    Route::post("/logout", [AuthController::class, 'logout'])->name("auth.logout");
});
