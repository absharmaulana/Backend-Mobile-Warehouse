<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data' => null,
            ], 401);
        }

        if (! in_array($user->role, $roles, true)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Forbidden. Role tidak diizinkan.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
