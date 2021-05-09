<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'rating',
        'content'
    ];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return "string";
    }

    public function bookable(){
        return $this->belongsTo('App\Models\Bookable');
    }

    public function booking(){
        return $this->belongsTo('App\Models\Booking');
    }
}
