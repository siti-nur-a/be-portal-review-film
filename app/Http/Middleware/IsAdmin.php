<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Roles;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $role = Roles::where('name', 'admin')->first();
        if ($user->role_id === $role->id) {
            return $next($request);
        }
        return response()->json([
            'message' => 'Only admin that allowed to access this page'
        ], 401);
    }
}
