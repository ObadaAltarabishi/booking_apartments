<?php

namespace App\Http\Controllers;

use App\Models\reviews;
use App\Models\apartments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'rating' => 'required|integer|between:1,5', // Stars from 1-5
        ]);

        $apartmentId = $request->apartment_id;
        $rating = $request->rating;
        $userId = Auth::id();


        // Check if user has already reviewed this apartment
        $existingReview = reviews::where('user_id', $userId)
            ->where('apartments_id', $apartmentId)
            ->first();

        if ($existingReview) {
            // User already reviewed - you might want to update instead
            return response()->json([
                'message' => 'You have already reviewed this apartment',
                'average_rating' => $existingReview->average_rating,
                'total_reviews' => $existingReview->count,
            ], 409);
        }

        $apartmentRating=apartments::find($apartmentId);
        $count=$apartmentRating->numOfratings ;
        $count= $count+1;
        $apartmentRating->numOfratings = $count;
        $ovr=$apartmentRating->overAllrating;
        $ovr=($rating+$ovr)/$count;
        $apartmentRating->overAllrating = $ovr;
        $apartmentRating->save();




        // Find or create review record for this apartment
        $review = reviews::Create(
           [ 'apartments_id' => $apartmentId,
            
                'reviewsAll' => 0,
                'user_id' => $userId,
                 // This might need adjustment - see note below
            ]
        );

      

        // Add the new rating
        $review->addRating($rating);
        

        return response()->json([
            'message' => 'Rating added successfully',
            'average_rating' => $review->reviewsAll,
            'total_reviews' => $review->count,
            'your_rating' => $rating,
            'user_id' => $userId,
            'count'=> $apartmentRating->numOfratings,
            'rating'=> $apartmentRating->overAllrating
        ]);
    }

  


   public function destroy(string $id)
    {
         $review = reviews::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'city not found'
            ], 404);
        }

        try {
           

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'review deleted successfully'
            ]);
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}