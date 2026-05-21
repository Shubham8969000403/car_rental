<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'user_id',
        'car_id',
        'rating',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', '_id');
    }
}
