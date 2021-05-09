<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableReviewIndexResource;
use Illuminate\Http\Request;
use App\Models\Bookable;

class BookableReviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $bookable = Bookable::findOrFail($id);

        return BookableReviewIndexResource::collection(
            $bookable->reviews()->latest()->get()
        ); // It's an array named "data"

        // return $bookable->reviews()->latest()->get(); // sort it and execute the query, it's an array with no name
    }
}
