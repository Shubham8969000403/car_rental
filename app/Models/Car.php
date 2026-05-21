<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Car extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cars';

    protected $fillable = [
        'brand',
        'model',
        'category',
        'location',
        'fuel_type',
        'transmission',
        'seat_count',
        'price_per_day',
        'availability',
        'image',
        'rating',
        'total_reviews'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'car_id', '_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'car_id', '_id');
    }
}
