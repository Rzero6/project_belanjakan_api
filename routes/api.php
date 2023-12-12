<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DetailTransactionController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TransactionController;
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


Route::get("/items", [ItemController::class, "index"]);
Route::get("/items/{id}", [ItemController::class, "show"]);
Route::get("/items/search/{id}/q={searchTerm}", [ItemController::class, "showByName"])->where('searchTerm', '.*');
Route::get("/items/cat/{id}", [ItemController::class, "showByCat"]);

Route::get("/categories", [CategoryController::class, "index"]);
Route::get("/categories/{id}", [CategoryController::class, "show"]);
Route::post("/categories", [CategoryController::class, "store"]);
Route::put("/categories/{id}", [CategoryController::class, "update"]);
Route::delete("/categories/{id}", [CategoryController::class, "destroy"]);

Route::get('/item/{id}/reviews', [ReviewController::class, "showPerItem"]);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post("/items", [ItemController::class, "store"]);
    Route::get("/items/auth/search/q={searchTerm?}", [ItemController::class, "showOnlyToOwnerByName"])
        ->where('searchTerm', '.*');
    Route::put("/items/{id}", [ItemController::class, "update"]);
    Route::delete("/items/{id}", [ItemController::class, "destroy"]);
    Route::patch("/items/{id}", [ItemController::class, "updateStock"]);

    Route::put("/user", [UserController::class, "update"]);
    Route::get("/user", [UserController::class, "show"]);

    Route::get("/coupons", [CouponController::class, "index"]);
    Route::post("/coupons", [CouponController::class, "store"]);
    Route::get("/coupons/{id}", [CouponController::class, "show"]);
    Route::put("/coupons/{id}", [CouponController::class, "update"]);
    Route::delete("/coupons/{id}", [CouponController::class, "destroy"]);

    Route::get("/carts", [CartController::class, "index"]);
    Route::post("/carts", [CartController::class, "store"]);
    Route::get("/carts/{id}", [CartController::class, "show"]);
    Route::put("/carts/{id}", [CartController::class, "update"]);
    Route::delete("/carts/{id}", [CartController::class, "destroy"]);

    Route::get("/transactions", [TransactionController::class, "index"]);
    Route::post("/transactions", [TransactionController::class, "store"]);
    Route::get("/transactions/{id}", [TransactionController::class, "show"]);
    Route::put("/transactions/{id}", [TransactionController::class, "update"]);

    Route::get("/transactions/{id}/details", [DetailTransactionController::class, "showByTransaction"]);
    Route::post("/transactions/details", [DetailTransactionController::class, "store"]);

    Route::post('/reviews', [ReviewController::class, "store"]);
    Route::get('/reviews/{id}', [ReviewController::class, "show"]);
    Route::put('/reviews/{id}', [ReviewController::class, "update"]);
    Route::delete('/reviews/{id}', [ReviewController::class, "destroy"]);
});
