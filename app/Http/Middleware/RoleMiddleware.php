<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$roles): Response
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token is not provided'], 401);
        }

        $user = Auth::user();
        $rolesArray = explode('|', $roles);
        // dd($user);

        if (!$user->hasAnyRole($rolesArray)) {
            return response()->json(['error' => 'You are not authorized to use this route'], 403);
        }
        return $next($request);
    }
}
