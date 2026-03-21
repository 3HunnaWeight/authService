<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentials;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\JwtService;
use App\Services\LoginService;
use App\Services\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private RegisterService $registerService, private LoginService $loginService, private JwtService $jwtService)
    {
    }

    public function register(RegisterUserRequest $request,): void
    {
        $this->registerService->register($request);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            return response()->json($this->loginService->login($request));
        } catch (InvalidCredentials $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function me(Request $request): JsonResponse
    {
        $decoded = $this->jwtService->decode($request->bearerToken());
        $user = User::query()->findOrFail($decoded->sub);
        return response()->json($user);
    }
}
