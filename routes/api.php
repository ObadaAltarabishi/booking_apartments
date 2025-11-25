<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\cityController;


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


    // Route::apiResource('apartments', ApartmentController::class);
    // Route::apiResource('bookings', BookingController::class);
    // Route::apiResource('reviews', ReviewController::class);
});
