<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function generateAccessToken(User $user): string
    {
        $payload = [
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => 'access',
            'iat' => time(),
            'exp' => time() + 900,
        ];

        return JWT::encode($payload, $this->getPrivateKey(), config('jwt.algorithm'));
    }

    public function generateRefreshToken(User $user): string
    {
        $payload = [
            'sub' => $user->id,
            'type' => 'refresh',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24 * 7,
        ];

        return JWT::encode($payload, $this->getPrivateKey(), config('jwt.algorithm'));
    }

    public function decode(string $token): \stdClass
    {
        return JWT::decode(
            $token,
            new Key(
                file_get_contents(config('jwt.public_key')),
                config('jwt.algorithm')
            )
        );
    }

    private function getPrivateKey(): string
    {
        return file_get_contents(config('jwt.private_key'));
    }
}
