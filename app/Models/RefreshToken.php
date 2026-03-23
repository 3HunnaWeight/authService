<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefreshToken extends Model
{

    protected $table = 'refresh_tokens';
    protected $fillable = ['user_id', 'token_hash', 'revoked_at', 'expires_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function createForUser(int $userId, string $token): self
    {
        return self::create([
            'user_id' => $userId,
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addDays(7),
        ]);
    }

    public static function findUserByToken(string $token): ?User
    {
        $tokenHash = hash('sha256', $token);

        $refreshToken = self::where('token_hash', $tokenHash)
            ->where('expires_at', '>', now())
            ->first();

        return $refreshToken?->user;
    }
}
