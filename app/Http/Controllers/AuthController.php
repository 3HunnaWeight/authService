<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentials;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\RefreshToken;
use App\Models\User;
use App\Services\JwtService;
use App\Services\LoginService;
use App\Services\RefreshTokenService;
use App\Services\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(private RegisterService $registerService, private LoginService $loginService, private JwtService $jwtService, private RefreshTokenService $refreshTokenService)
    {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        return response()->json($this->registerService->register($request), Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            return response()->json($this->loginService->login($request), Response::HTTP_OK);
        } catch (InvalidCredentials $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->refreshTokenService->revoke($request->input('refresh_token'));

        return response()->json(['message' => 'Logged out'], Response::HTTP_OK);
    }

    public function me(Request $request): JsonResponse
    {
        $userId = $request->attributes->get('user_id');
        $user = User::findOrFail($userId);
        return response()->json($user, Response::HTTP_OK);
    }

    public function refresh(Request $request): JsonResponse
    {
        $token = $request->input('refresh_token');

        $user = RefreshToken::findUserByToken($token);

        if (!$user) {
            return response()->json(['message' => 'Invalid refresh token'], Response::HTTP_UNAUTHORIZED);
        }

        $this->refreshTokenService->revoke($token);

        $newRefreshToken = $this->jwtService->generateRefreshToken($user);

        RefreshToken::createForUser($user->id, $newRefreshToken);

        return response()->json([
            'access_token' => $this->jwtService->generateAccessToken($user),
            'refresh_token' => $newRefreshToken,
        ], Response::HTTP_OK);
    }
}
