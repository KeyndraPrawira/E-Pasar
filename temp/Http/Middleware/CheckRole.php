<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        //1. Cek apakah user sudah login
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

         // 2. Ambil user yang sedang login
        $user = Auth::guard('sanctum')->user();

        // 3. Periksa apakah role user ada dalam daftar role yang diizinkan (...$roles)
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak memiliki akses
        return response()->json(['message' => 'Ditolak: Anda tidak memiliki akses',
        'role' => $user->role,
        
        ], 403);
    }
}
