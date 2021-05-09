<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bookable;
use App\Models\Review;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bookable::all()->each(function(Bookable $bookable) {
            $reviews = Review::factory(random_int(5, 30))->make(); // make but not store yet, because using create() will ask for bookable_id and relate to rating, which is not in the factory
            $bookable->reviews()->saveMany($reviews);
        });
    }
}
