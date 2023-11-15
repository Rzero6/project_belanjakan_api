<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

//Route::apiResource("item",ItemController::class);
Route::get("/items", [ItemController::class, "index"]);
Route::get("/items/{id}", [ItemController::class, "show"]);
Route::get("/items/search/q={searchTerm}", [ItemController::class, "showByName"]);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post("/items", [ItemController::class, "store"]);
    Route::put("/items/{id}", [ItemController::class, "update"]);
    Route::delete("/items/{id}", [ItemController::class, "destroy"]);

    Route::put("/user", [UserController::class, "update"]);
    Route::get("/user", [UserController::class, "show"]);
});
