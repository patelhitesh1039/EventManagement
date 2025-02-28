<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Event Management API",
 *     version="1.0.0",
 *     description="The Event Management API allows users to create, manage, and book events effortlessly. Users can register and log in to the platform, create and manage their events, and book seats for available events. The API provides endpoints for handling event details, user authentication, role-based access control, and booking management. It supports features like secure JWT authentication, validation of event parameters, and robust error handling. This API serves as a comprehensive solution for event organizers and attendees, facilitating efficient event management and seamless user experiences."
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your JWT token"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
