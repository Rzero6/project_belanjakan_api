<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
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

Route::post("/register",[AuthController::class,"register"]);
Route::post("/login",[AuthController::class,"login"]);

//Route::apiResource("item",ItemController::class);
Route::get("/items",[ItemController::class,"index"]);
Route::post("/items",[ItemController::class,"store"]);
Route::get("/items/{id}",[ItemController::class,"show"]);
Route::put("/items/{id}",[ItemController::class,"update"]);
Route::delete("/items/{id}",[ItemController::class,"destroy"]);
Route::get("/items/search/q={searchTerm}",[ItemController::class,"showByName"]);
