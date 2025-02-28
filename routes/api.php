<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('/users', [UserController::class, 'getUser'])->name('users');
        Route::put('/users/{id}/role', [UserController::class, 'updateUserRole'])->name('updateUserRole');
        Route::delete('/events/{id}', [EventController::class, 'deleteEvent'])->name('deleteEvent');

    });


    Route::group(['middleware' => ['role:admin|event_creator']], function() {
        Route::post('/events', [EventController::class, 'createEvent'])->name('createEvent');
        Route::put('/events/{id}', [EventController::class, 'updateEvent'])->name('updateEvent');
        Route::get('/my-events', [EventController::class, 'myEvents'])->name('myEvents');
    });
    
    Route::get('/events', [EventController::class, 'getAllEvents'])->name('getAllEvents');

    Route::post('/bookings', [BookingController::class, 'createBooking'])->name('createBooking');
    Route::delete('/bookings/{id}', [BookingController::class, 'deleteBooking'])->name('deleteBooking');
});





