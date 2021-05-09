<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Bookable extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content'
    ];

    public function bookings() {
        return $this->hasMany('App\Models\Booking');
    }

    public function availableFor($from, $to): bool {
        return 0 === $this->bookings()->betweenDates($from, $to)->count();
        // If ($this->bookings()->betweenDates($from, $to)->count() === 0){return 0} 
    }

    public function reviews() {
        return $this->hasMany('App\Models\Review');
    }

    public function priceFor($from, $to) {
        $days = (new Carbon($from))->diffInDays(new Carbon($to)) + 1;
        $price = $days * $this->price;

        // return response()->json([
        //     'data' => [
        //         'total' => $price,
        //         'breakdown' => [
        //             $this->price => $days
        //         ]
        //     ]
        // ]);
        return [
            'total' => $price,
            'breakdown' => [
                $this->price => $days
            ]
        ];
    }

}
