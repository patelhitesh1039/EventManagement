<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

   /**
     * @OA\Post(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Create a new event",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Event title"
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Event description"
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Event location"
     *     ),
     *     @OA\Parameter(
     *         name="start_time",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date-time", example="2025-12-02 01:01:01"),
     *         description="Event start time in format YYYY-MM-DD HH:MM:SS"
     *     ),
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time", ),
     *         description="Event end time in format YYYY-MM-DD HH:MM:SS"
     *     ),
     *     @OA\Parameter(
     *         name="capacity",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=100),
     *         description="Event capacity"
     *     ),
     *     @OA\Parameter(
     *         name="created_by",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID who created the event"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Event created successfully"),
     *             @OA\Property(property="event", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start_time',
            'capacity' => 'nullable|integer|min:1',
            'created_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::create($request->all());

        return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
    }
    /**
     * @OA\Get(
     *     path="/api/my-events",
     *     tags={"Events"},
     *     summary="Get events by user ID",
     *     @OA\Parameter(
     *         name="userId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Events retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Events retrieved successfully"),
     *             @OA\Property(property="events", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function myEvents(Request $request)
    {
        $userId = $request->query('userId');
        $events = Event::where('created_by',$userId)->get();
        return response()->json(['message' => 'Events retrieved successfully', 'events' => $events], 200);  
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Update an event",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Event ID"
     *     ),
     *     @OA\Parameter(
     *         name="userId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Event title"
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Event description"
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Event location"
     *     ),
     *     @OA\Parameter(
     *         name="start_time",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date-time", example="2025-12-02 01:01:01"),
     *         description="Event start time in format YYYY-MM-DD HH:MM:SS"
     *     ),
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date-time", example="2025-12-02 01:01:01"),
     *         description="Event end time in format YYYY-MM-DD HH:MM:SS"
     *     ),
     *     @OA\Parameter(
     *         name="capacity",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=100),
     *         description="Event capacity"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Event updated successfully"),
     *             @OA\Property(property="event", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Event not found or you are not authorized to update this event"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function updateEvent(Request $request, $id)
    {
        $userId = $request->query('userId');

        $event = Event::where('id', $id)->where('created_by', $userId)->first();

        if (!$event) {
            return response()->json(['error' => 'Event not found or you are not authorized to update this event', 'userId' => $userId], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start_time',
            'capacity' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'userId' => $userId], 422);
        }

        $event->update($request->all());

        return response()->json(['message' => 'Event updated successfully', 'event' => $event], 200);
    }


     /**
     * @OA\Get(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Get all events",
     *     @OA\Response(
     *         response=200,
     *         description="Events retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Events retrieved successfully"),
     *             @OA\Property(property="events", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAllEvents()
    {
        $events = Event::all();
        return response()->json(['message' => 'Events retrieved successfully', 'events' => $events], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Delete an event",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Event ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Event deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Event not found")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function deleteEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }


}
