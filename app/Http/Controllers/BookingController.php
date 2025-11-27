<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\apartments;
use App\Models\User;
use App\Models\city;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display all bookings for the authenticated user
     */
    public function show(Request $request): JsonResponse
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        try {
            // Get all bookings for the authenticated user
            $bookings = Booking::where('user_id', $request->user()->id)
                ->with(['apartment', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Bookings retrieved successfully',
                'data' => $bookings,
                'count' => $bookings->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function BookingApartment(Request $request, string $id): JsonResponse
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // Verify user exists in database
        $user = User::find($request->user()->id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User account not found. Please login again.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date_format:m/d/Y',
            'endDate' => 'required|date_format:m/d/Y|after:startDate',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the apartment using the id from route parameter
            $apartment = apartments::find($id);

            if (!$apartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apartment not found'
                ], 404);
            }

            // Check if apartment is available
            if ($apartment->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apartment is not available for booking'
                ], 422);
            }

            // Parse dates from m/d/Y format
            $startDate = Carbon::createFromFormat('m/d/Y', $request->startDate);
            $endDate = Carbon::createFromFormat('m/d/Y', $request->endDate);

            // Check if start date is not in the past
            if ($startDate->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date cannot be in the past'
                ], 422);
            }

            // Create booking using verified user ID
            $booking = Booking::create([
                'apartments_id' => $apartment->id,
                'user_id' => $user->id,
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d'),
            ]);

            // Optionally update apartment status to unavailable
             $apartment->update(['status' => 'unavailable']);

            return response()->json([
                'success' => true,
                'message' => 'Apartment booked successfully',
                'data' => $booking->load('apartment', 'user')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to book apartment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
