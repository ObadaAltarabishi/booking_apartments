<?php

namespace App\Http\Controllers;

use App\Models\city;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class cityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
         try {
            $city = city::all();
            return response()->json([
                'success' => true,
                'data' => $city
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();


            $city = city::create($data);
             return response()->json([
                'success' => true,
                'message' => 'city created successfully',
                'data' => $city
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create city',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        try {
            $city = city::find($id);

            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'city not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $city
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch city',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
         $city = city::find($id);

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'city not found'
            ], 404);
        }

        try {
           

            $city->delete();

            return response()->json([
                'success' => true,
                'message' => 'city deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete city',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    
    public function Allcities(): JsonResponse
{
    try {
        $cityNames = city::pluck('name'); // Get only the 'name' column
        return response()->json([
            'success' => true,
            'data' => $cityNames
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch cities',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
