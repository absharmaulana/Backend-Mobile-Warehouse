<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ApiResponse;

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return $this->errorResponse('Email atau password tidak valid.', [
                'email' => ['Email atau password tidak valid.'],
            ], 422);
        }

        if (! $user->is_active) {
            return $this->errorResponse('Akun tidak aktif.', null, 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('mobile')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => $user,
            'role' => $user->role,
            'dashboard_key' => $user->dashboardKey(),
        ], 'Login success');
    }
}