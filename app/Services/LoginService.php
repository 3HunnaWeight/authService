<?php

namespace App\Services;

use App\Exceptions\InvalidCredentials;
use App\Http\Requests\LoginUserRequest;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function __construct(private JwtService $jwtService)
    {
    }

    public function login(LoginUserRequest $request): array
    {
        $user = User::query()
            ->where('email', $request->validated('email'))
            ->first();

        if (!$user || !Hash::check($request->validated('password'), $user->password)) {
            throw new InvalidCredentials();
        }

        $accessToken = $this->jwtService->generateAccessToken($user);
        $refreshToken = $this->jwtService->generateRefreshToken($user);

        RefreshToken::createForUser($user->id, $refreshToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ];
    }
}
