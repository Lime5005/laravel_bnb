<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;

class ReviewController extends Controller
{
    public function show($id) {
        return new ReviewResource(Review::findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'id' => 'required|size:36|unique:reviews',
            'rating' => 'required|in:1,2,3,4,5',
            'content' => 'required|min:2',
        ]);

        // We don't save data if the booking is not found
        $booking = Booking::findByReviewKey($data['id']);
        if (null === $booking) {
            return abort(404);
        }

        // The user will not be able to review again with an empty review_key
        $booking->review_key = '';
        $booking->save();

        $review = Review::make($data);
        $review->booking_id = $booking->id;
        $review->bookable_id = $booking->bookable_id;
        $review->save();

        // This is 'created_at', used for verify
        return new ReviewResource($review);
    }
}
