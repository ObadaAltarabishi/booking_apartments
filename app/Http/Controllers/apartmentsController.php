<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\apartments;
use App\Models\User;
use App\Models\city;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class apartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //  get all apartments
        $apartments = apartments::all();
        try {
            return response()->json([
            'success' => true,
            'data' => $apartments
        ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch apartments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
{
    // Check if user is authenticated
    if (!$request->user()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Please login first.'
        ], 401);
    }

    // Additional check to ensure user exists in database
    $user = User::find($request->user()->id);
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User account not found. Please login again.'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'status' => 'nullable|in:available,unavailable',
        'city_id' => 'required|exists:city,id',
        'images' => 'nullable|array|max:10',
        'images.*' => 'nullable|string|max:500'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $data = $request->only(['title', 'description', 'price', 'status', 'city_id', 'images']);
        
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'available';
        }

        // Always use authenticated user's ID (verified to exist)
        $data['user_id'] = $user->id;

        // Ensure images is properly formatted as array
        if (isset($data['images']) && is_string($data['images'])) {
            $data['images'] = json_decode($data['images'], true);
        }

        $apartment = apartments::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Apartment created successfully',
            'data' => $apartment
            // 'images'=>$apartment->images[1]
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create apartment',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //  get apartment by id
        $apartment = apartments::find($id);
        try {
            if (!$apartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apartment not found'
                ], 404);
            }
            return response()->json([
            'success' => true,
            'data' => $apartment
        ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch apartment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ensure user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        try {
            $apartment = apartments::find($id);

            if (!$apartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apartment not found'
                ], 404);
            }

            // User can only update own apartment
            if ($apartment->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this apartment.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'status' => 'sometimes|required|in:available,unavailable',
                'city_id' => 'sometimes|required|exists:city,id',
                'images' => 'sometimes|nullable|array|max:10',
                'images.*' => 'sometimes|nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if (array_key_exists('images', $data) && is_string($data['images'])) {
                $data['images'] = json_decode($data['images'], true);
            }

            $apartment->fill($data);
            $apartment->save();

            return response()->json([
                'success' => true,
                'message' => 'Apartment updated successfully',
                'data' => $apartment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update apartment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        try {
            $apartment = apartments::find($id);

            if (!$apartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apartment not found'
                ], 404);
            }

            if ($apartment->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete this apartment.'
                ], 403);
            }

            $apartment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Apartment deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete apartment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
