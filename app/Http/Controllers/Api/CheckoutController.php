<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookable;
use App\Models\Booking;
use App\Models\Address;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // "Customer" is a JSON format data in our API
        // Using * to loop through in the elements of each array
        $data1 = $request->validate([
            'bookings' => 'required|array|min:1',

            'bookings.*.bookable_id' => 'required|exists:bookables,id',
            'bookings.*.from' => 'required|date|after_or_equal:today',
            'booking.*.to' => 'required|date|after_or_equal:bookings.*.from',

            'customer.firstname' => 'required|min:2',
            'customer.lastname' => 'required|min:2',
            'customer.email' => 'required|email',
            'customer.street' => 'required|min:3',
            'customer.city' => 'required|min:2',
            'customer.country' => 'required|min:2',
            'customer.state' => 'required|min:2',
            'customer.zip' => 'required|min:2',
        ]);
        
        // dd($data1); It will not check the availability.

        $data = array_merge($data1, $request->validate([
            'bookings.*' => ['required', function($attribute, $value, $fail){
                // dd($attribute, $value);
                $bookable = Bookable::findOrFail($value['bookable_id']);
                if (!$bookable->availableFor($value['from'], $value['to'])) {
                    $fail('The given dates are not available for this hotel');
                }
            }]
        ]));

        // dd($data); // The availability is checked, and the two data merged.

        // Assign the data returned in API to database `bookings` table
        $bookingsData = $data['bookings'];
        $addressData = $data['customer'];

        $bookings = collect($bookingsData)->map(function ($bookingData) use ($addressData) {
            $bookable = Bookable::findOrFail($bookingData['bookable_id']);
            $booking = new Booking();
            $booking->from = $bookingData['from'];
            $booking->to = $bookingData['to'];
            $booking->price = $bookable->priceFor($booking->from, $booking->to)['total'];
            // $booking->bookable_id = $bookingData['bookable_id'];
            $booking->bookable()->associate($bookable);
            $booking->address()->associate(Address::create($addressData));

            $booking->save();
            return $booking;
        });

        return $bookings;
    }
}
