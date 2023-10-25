<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SportArticleController;
use App\Http\Controllers\ReservationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public routes
Route::get('/sport-articles', [SportArticleController::class, 'all']);
Route::get('/sport-articles/{id}', [SportArticleController::class, 'detail']);
Route::post('/reservations', [ReservationController::class, 'store']);

Route::post('/register', [AuthController::class, "register"]);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::fallback(function () {
    return response()->json(['error' => 'Unauthenticated. Please provide a valid token.'], 401);
});

//backoffice routes
Route::middleware('auth:api')->group(function() {
Route::get('/reservations', [ReservationController::class, 'all']);
Route::get('/reservations/{id}', [ReservationController::class, 'detail']);

Route::post('/sport-articles', 'SportArticleController@store');
Route::delete('/reservations/{id}', 'ReservationController@reject');

Route::put('/reservations/{id}', 'ReservationController@update');
Route::put('/reservations/approve/{id}', 'ReservationController@approve');
});



