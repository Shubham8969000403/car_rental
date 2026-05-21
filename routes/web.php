<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/car/{id}', [CarController::class, 'show'])->name('cars.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/book/{id}', [BookingController::class, 'store'])->name('booking.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/bookings/approve/{id}', [AdminController::class, 'approveBooking'])->name('admin.booking.approve');
    Route::post('/bookings/reject/{id}', [AdminController::class, 'rejectBooking'])->name('admin.booking.reject');
    Route::get('/cars', [AdminController::class, 'cars'])->name('admin.cars');
    Route::post('/cars/store', [AdminController::class, 'storeCar'])->name('admin.car.store');
    Route::post('/cars/update/{id}', [AdminController::class, 'updateCar'])->name('admin.car.update');
    Route::post('/cars/delete/{id}', [AdminController::class, 'deleteCar'])->name('admin.car.delete');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
});
