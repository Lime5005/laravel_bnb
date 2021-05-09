<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $from = Carbon::instance($this->faker->dateTimeBetween('-1 months', '+1 months')); // From one month ago to one month later
        $to = (clone $from)->addDays(random_int(0, 14)); // From 1 day to 15 days
        return [
            'from' => $from,
            'to' => $to,
            'price' => random_int(200, 5000)
        ];
    }
}
