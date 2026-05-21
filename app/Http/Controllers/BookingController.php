<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:mongodb.cars,_id',
            'pickup_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:pickup_date',
            'pickup_location' => 'required|string',
            'return_location' => 'required|string'
        ]);

        $car = Car::findOrFail($request->car_id);
        $pickupDate = Carbon::createFromFormat('Y-m-d', $request->pickup_date);
        $returnDate = Carbon::createFromFormat('Y-m-d', $request->return_date);
        $days = $returnDate->diffInDays($pickupDate);
        $totalAmount = $car->price_per_day * $days;

        $existingBooking = Booking::where('car_id', $request->car_id)
            ->where('status', '!=', 'rejected')
            ->where(function($q) use ($pickupDate, $returnDate) {
                $q->whereBetween('pickup_date', [$pickupDate, $returnDate])
                  ->orWhereBetween('return_date', [$pickupDate, $returnDate]);
            })
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'Car is not available for selected dates');
        }

        Booking::create([
            'user_id' => Auth::id(),
            'car_id' => $request->car_id,
            'pickup_date' => $pickupDate,
            'return_date' => $returnDate,
            'pickup_location' => $request->pickup_location,
            'return_location' => $request->return_location,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->notes ?? ''
        ]);

        return redirect('/dashboard')->with('success', 'Booking request sent successfully! Waiting for admin approval.');
    }
}
