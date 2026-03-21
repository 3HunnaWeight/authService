<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function generate(User $user)
    {

        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        $privateKey = file_get_contents(config('jwt.private_key'));

        return JWT::encode($payload, $privateKey, config('jwt.algorithm'));
    }

    public function decode(string $token)
    {
        return JWT::decode(
            $token,
            new Key(
                file_get_contents(config('jwt.public_key')),
                config('jwt.algorithm')
            )
        );
    }
}
