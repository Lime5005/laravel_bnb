<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\BookingByReviewShowResource;

class BookingByReviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $reviewkey)
    {
        // return abort(500); // To test all errors that are other than 404, if it will render what we asked in `Review.vue`
        $booking = Booking::findByReviewKey($reviewkey);
        return $booking ? new BookingByReviewShowResource($booking) : abort(404);
        // return new BookingByReviewShowResource(Booking::findByReviewKey($reviewkey) ?? abort(404));
    }
}
