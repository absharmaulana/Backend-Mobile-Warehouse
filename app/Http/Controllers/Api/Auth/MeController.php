<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse([
            'user' => $user,
            'role' => $user->role,
            'dashboard_key' => $user->dashboardKey(),
        ], 'Current user profile');
    }
}