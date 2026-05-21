<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->location) {
            $query->where('location', $request->location);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->min_price && $request->max_price) {
            $query->whereBetween('price_per_day', [$request->min_price, $request->max_price]);
        }

        if ($request->availability) {
            $query->where('availability', $request->availability);
        }

        $cars = $query->paginate(12);
        $cities = ['Delhi', 'Mumbai', 'Bangalore', 'Hyderabad', 'Pune', 'Ahmedabad', 'Kolkata', 'Chennai', 'Jaipur', 'Surat', 'Lucknow', 'Chandigarh'];
        $categories = ['Hatchback', 'Sedan', 'SUV', 'Luxury', 'Sports', 'Electric'];

        return view('cars.index', compact('cars', 'cities', 'categories'));
    }

    public function show($id)
    {
        $car = Car::findOrFail($id);
        $reviews = Review::where('car_id', $id)->with('user')->paginate(5);
        $cities = ['Delhi', 'Mumbai', 'Bangalore', 'Hyderabad', 'Pune', 'Ahmedabad', 'Kolkata', 'Chennai', 'Jaipur', 'Surat', 'Lucknow', 'Chandigarh'];

        return view('cars.show', compact('car', 'reviews', 'cities'));
    }
}
