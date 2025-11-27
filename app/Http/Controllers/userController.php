<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user.
     */
    // public function store(Request $request): JsonResponse
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:8',
    //         'phone' => 'required|string|unique:users,phone',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     try {
    //         $data = $request->all();
    //         $data['password'] = Hash::make($request->password);

    //         // Handle image upload
    //         if ($request->hasFile('image')) {
    //             $imagePath = $request->file('image')->store('users', 'public');
    //             $data['image'] = $imagePath;
    //         }

    //         $user = User::create($data);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User created successfully',
    //             'data' => $user
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to create user',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Update the specified user.
     */
    // public function update(Request $request, $id): JsonResponse
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found'
    //         ], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         // 'name' => 'sometimes|string|max:255',
    //         // 'email' => 'sometimes|email|unique:users,email,' . $id,
    //         // 'password' => 'sometimes|string|min:8',
    //         // 'phone' => 'sometimes|string|unique:users,phone,' . $id,
    //         'image' => 'somtimes|image|mimes:jpeg,png,jpg,gif|max:2048'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     try {
    //         $data = $request->all();

    //         // // Hash password if provided
    //         // if ($request->has('password')) {
    //         //     $data['password'] = Hash::make($request->password);
    //         // } else {
    //         //     unset($data['password']);
    //         // }

    //         // Handle image upload
    //         if ($request->hasFile('image')) {
    //             // Delete old image if exists
    //             if ($user->image && Storage::disk('public')->exists($user->image)) {
    //                 Storage::disk('public')->delete($user->image);
    //             }

    //             $imagePath = $request->file('image')->store('users', 'public');
    //             $data['image'] = $imagePath;
    //         }

    //         $user->update($data);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User updated successfully',
    //             'data' => $user
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to update user',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Remove the specified user.
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            // Delete user image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's apartments.
     */
    public function getUserApartments($id): JsonResponse
    {
        try {
            $user = User::with('apartments')->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user->apartments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user apartments',
                'error' => $e->getMessage()
            ], 500);
            
        }
    }

    /**
     * Get user's bookings.
     */
    public function getUserBookings($id): JsonResponse
    {
        try {
            $user = User::with('bookings.apartment')->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user->bookings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's reviews.
     */
    public function getUserReviews($id): JsonResponse
    {
        try {
            $user = User::with('reviews.apartment')->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user->reviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     public function updateImage(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('users', 'public');
            
            // Update user's image
            $user->update(['image' => $imagePath]);

            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'data' => [
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeImage($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            // Delete image file from storage if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Set image to null in database
            $user->update(['image' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addBalance(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'balance' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',   
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Ensure balance is numeric; default to 0 if null
            $currentBalance = $user->balance ?? 0;
            $user->update(['balance' => $currentBalance + $request->balance]);

            return response()->json([
                'success' => true,
                'message' => 'Balance added successfully',  
                'data' => [
                    
                    'balance' => $user->balance
                ], 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add balance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBalance(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Balance fetched successfully',
            'data' => [
                'user' => $user,
                'balance' => $user->balance
            ],
        ]);
    }

    

    
}
