<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // // Your other protected routes here
    Route::get('users', [UserController::class,'index']);
    Route::get('users/{id}', [UserController::class,'index']);
    Route::post('users', [UserController::class,'store']);
    Route::put('users', [UserController::class,'update']);
    Route::delete('users', [UserController::class,'delete']);
    Route::get('users/{id}/apartments', [UserController::class, 'getUserApartments']);
    Route::get('users/{id}/bookings', [UserController::class, 'getUserBookings']);
    Route::get('users/{id}/reviews', [UserController::class, 'getUserReviews']);



    // Route::apiResource('apartments', ApartmentController::class);
    // Route::apiResource('bookings', BookingController::class);
    // Route::apiResource('reviews', ReviewController::class);
});
