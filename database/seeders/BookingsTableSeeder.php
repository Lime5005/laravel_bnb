<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bookable;
use App\Models\Booking;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bookable::all()->each(function(Bookable $bookable) {
            // For each model, we want to do something
            $booking = Booking::factory()->make();// This generate one single booking
            $bookings = collect([$booking]); // we can use this collection to generate random number of bookings and add items to it.
            for ($i = 0; $i < random_int(1,20); $i++) {
                // In this way, the "from" day is always one day later than "to" day.
                $from = (clone $booking['to'])->addDays(random_int(1, 14));
                $to = (clone $from)->addDays(random_int(0, 14));
                // Mass assignment, $fillable = ['from', 'to'] in Booking model.
                $booking = Booking::make([
                    'from' => $from,
                    'to' => $to,
                    'price' => random_int(200, 5000)
                ]);
                $bookings->push($booking);
            }
            $bookable->bookings()->saveMany($bookings);
        });
    }
}
