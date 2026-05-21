<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->_id)->orderBy('created_at', 'desc')->with('car')->paginate(10);
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->_id)->count(),
            'pending' => Booking::where('user_id', $user->_id)->where('status', 'pending')->count(),
            'approved' => Booking::where('user_id', $user->_id)->where('status', 'approved')->count(),
            'rejected' => Booking::where('user_id', $user->_id)->where('status', 'rejected')->count()
        ];

        return view('customer.dashboard', compact('bookings', 'stats'));
    }
}
