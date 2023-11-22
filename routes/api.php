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
Route::get('/sport-articles', [SportArticleController::class, 'all']); //done
Route::get('/sport-articles/{id}', [SportArticleController::class, 'detail']); //done
Route::post('/reservations', [ReservationController::class, 'create']);

Route::post('/register', [AuthController::class, "register"]); //done
Route::post('/login', [AuthController::class, 'login'])->name('login'); //done

Route::fallback(function () {
    return response()->json(['error' => 'Unauthenticated. Please provide a valid token.'], 401);
});

//backoffice routes
//Route::middleware('auth:api')->group(function() {
Route::get('/reservations', [ReservationController::class, 'all']); //done
Route::get('/reservations/{id}', [ReservationController::class, 'detail']); //done

Route::post('/sport-articles', [SportArticleController::class, 'create']); //done
Route::delete('/sport-articles/{id}', [SportArticleController::class, 'delete']); //done
Route::get('/sport-articles/image/{name}', [SportArticleController::class, 'downloadImage'])->name('image'); //done
Route::put('/sport-articles/{id}', [SportArticleController::class, 'update']); //done
Route::put('/reservations/{id}', [ReservationController::class, 'approve']); //done

Route::delete('/reservations/{id}', [ReservationController::class, 'delete']); //done
Route::delete('/reservations/{id}/cancel', [ReservationController::class, 'cancel']); //done
//});
