<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Support\Str;


class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['from', 'to'];   

    public function bookable() {
        return $this->belongsTo('App\Models\Bookable');
    }

    public function scopeBetweenDates(Builder $query, $from, $to) {
        return $query->where('to', '>=', $from)->where('from', '<=', $to);
    }

    public function review() {
        return $this->hasOne('App\Models\Review');
    }

    public static function boot() {
        parent::boot();
        static::creating(function ($booking) {
            $booking->review_key = Str::uuid();
        });
    }

    public static function findByReviewKey(string $reviewkey): ?Booking {
        return static::where('review_key', $reviewkey)
        ->with('bookable')
        ->get()->first();
    }

    public function address() {
        return $this->belongsTo('App\Models\Address');
    }
}
