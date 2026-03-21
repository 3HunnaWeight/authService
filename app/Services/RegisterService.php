<?php

namespace App\Services;

use App\Http\Requests\RegisterUserRequest;
use App\Kafka\EventDispatcher;
use App\Kafka\Events\UserCreatedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{

    public function __construct(private EventDispatcher $eventDispatcher)
    {
    }

    public function register(RegisterUserRequest $request): void
    {
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $this->eventDispatcher->dispatch(new UserCreatedEvent($user));
    }
}
