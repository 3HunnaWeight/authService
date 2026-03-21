<?php

namespace App\Services;

use App\Exceptions\InvalidCredentials;
use App\Http\Requests\LoginUserRequest;
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

        $token = $this->jwtService->generate($user);

        return [
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ];
    }
}
