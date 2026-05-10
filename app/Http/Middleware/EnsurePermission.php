<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data' => null,
            ], 401);
        }

        if (! $user->hasAnyPermission($permissions)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Forbidden. Permission tidak diizinkan.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
