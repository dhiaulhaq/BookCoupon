<?php

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

Route::apiResource('/books', App\Http\Controllers\Api\BookController::class);
Route::apiResource('/coupons', App\Http\Controllers\Api\CouponController::class);
Route::apiResource('/checkout', App\Http\Controllers\Api\CheckoutController::class);
