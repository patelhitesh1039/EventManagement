<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         ))
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getUser()
    {
        $user = User::all();
        return response()->json($user);
    }


    /**
     * @OA\Put(
     *     path="/api/users/{id}/role",
     *     tags={"Users"},
     *     summary="Update user role",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\Parameter(
     *         name="roleId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Role ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User role updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User role updated successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function updateUserRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'roleId' => 'required|string|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $role = Role::find($request->roleId);
        $user = User::find($id);
        $user->syncRoles($role->name);
        return response()->json(['message' => 'User role updated successfully', 'user' => $user], 200);
    }
}
