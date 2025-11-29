<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\cityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\apartmentsController;
use App\Http\Controllers\reviewsController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    //auth api's
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
   //user api's
    Route::get('users', [UserController::class,'index']);
    Route::get('users/{id}', [UserController::class,'show']);
    //Route::post('users', [UserController::class,'store']);
    Route::post('users/{id}/image', [UserController::class, 'updateImage']);
    Route::delete('users/{id}/image', [UserController::class, 'removeImage']);
    Route::delete('users/{id}', [UserController::class,'destroy']);
    Route::get('users/{id}/apartments', [UserController::class, 'getUserApartments']);
    Route::get('users/{id}/bookings', [UserController::class, 'getUserBookings']);
    Route::get('users/{id}/reviews', [UserController::class, 'getUserReviews']);

    //city api's
    Route::get('city', [cityController::class,'index']);
    Route::get('city/{id}', [cityController::class,'show']);
    Route::post('city', [cityController::class,'store']);
    Route::delete('city/{id}', [cityController::class,'destroy']);
    Route::get('city/show/all', [cityController::class,'Allcities']);

    //apartments api's
    Route::post('apartments', [apartmentsController::class,'store']);
    Route::get('apartments', [apartmentsController::class,'index']);
    Route::get('apartments/{id}', [apartmentsController::class,'show']);
    Route::post('apartments/{id}', [apartmentsController::class,'update']);
    Route::delete('apartments/{id}', [apartmentsController::class,'destroy']);  

    Route::post('/reviews', [reviewsController::class, 'store']);
    Route::delete('/reviews/{id}', [reviewsController::class, 'destroy']);

    // Route::apiResource('apartments', ApartmentController::class);
    Route::post('bookings/{id}', [BookingController::class,'BookingApartment']);
    Route::get('bookings', [BookingController::class,'show']);  

    //user balance api's
    Route::post('users/balance', [UserController::class,'addBalance']);
    Route::get('users/balance', [UserController::class,'getBalance']);
    // Route::apiResource('reviews', ReviewController::class);
});
