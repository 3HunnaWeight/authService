<?php

namespace App\Services;

use App\Models\RefreshToken;

class RefreshTokenService
{
    public function revoke(string $token): void
    {
        $token = RefreshToken::where('token_hash', hash('sha256', $token))->whereNull('revoked_at')->first();
        $token->update(['revoked_at' => now()]);
    }
}
