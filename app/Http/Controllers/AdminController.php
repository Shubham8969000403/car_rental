<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'customer')->count(),
            'total_cars' => Car::count(),
            'total_bookings' => Booking::count(),
            'pending_requests' => Booking::where('status', 'pending')->count(),
            'approved_requests' => Booking::where('status', 'approved')->count(),
            'rejected_requests' => Booking::where('status', 'rejected')->count()
        ];

        $recent_bookings = Booking::orderBy('created_at', 'desc')->with('user', 'car')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    public function bookings()
    {
        $bookings = Booking::orderBy('created_at', 'desc')->with('user', 'car')->paginate(15);
        return view('admin.bookings', compact('bookings'));
    }

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'approved']);
        $car = Car::findOrFail($booking->car_id);
        $car->update(['availability' => 'booked']);

        return back()->with('success', 'Booking approved successfully!');
    }

    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'rejected']);

        return back()->with('success', 'Booking rejected successfully!');
    }

    public function cars()
    {
        $cars = Car::paginate(15);
        return view('admin.cars', compact('cars'));
    }

    public function storeCar(Request $request)
    {
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'category' => 'required|in:Hatchback,Sedan,SUV,Luxury,Sports,Electric',
            'location' => 'required|string',
            'fuel_type' => 'required|string',
            'transmission' => 'required|in:Manual,Automatic',
            'seat_count' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'availability' => 'required|in:available,booked',
            'image' => 'nullable|url',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        Car::create($request->all());
        return redirect('/admin/cars')->with('success', 'Car added successfully!');
    }

    public function updateCar(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'category' => 'required|in:Hatchback,Sedan,SUV,Luxury,Sports,Electric',
            'location' => 'required|string',
            'fuel_type' => 'required|string',
            'transmission' => 'required|in:Manual,Automatic',
            'seat_count' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'availability' => 'required|in:available,booked',
            'image' => 'nullable|url',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        $car->update($request->all());
        return redirect('/admin/cars')->with('success', 'Car updated successfully!');
    }

    public function deleteCar($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        return redirect('/admin/cars')->with('success', 'Car deleted successfully!');
    }

    public function users()
    {
        $users = User::where('role', 'customer')->paginate(15);
        return view('admin.users', compact('users'));
    }
}
