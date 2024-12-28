<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user || !$user->email_verified_at) {
            return response()->json([
                'message' => 'User belum verifikasi'
            ], 403);
        }

        // Jika email sudah diverifikasi, lanjutkan ke request berikutnya
        return $next($request);
    }
}
