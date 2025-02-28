<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Create a new booking",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\Parameter(
     *         name="event_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Event ID"
     *     ),
     *     @OA\Parameter(
     *         name="seats",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="Number of seats to book"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", enum={"booked","canceled"}),
     *         description="Booking status"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Booking created successfully"),
     *             @OA\Property(property="booking", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'seats' => 'required|integer|min:1',
            'status' => 'required|in:booked,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking = Booking::create($request->all());

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Delete a booking",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Booking ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking canceled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Booking canceled successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Booking not found")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function deleteBooking($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking canceled successfully'], 200);
    }


}
