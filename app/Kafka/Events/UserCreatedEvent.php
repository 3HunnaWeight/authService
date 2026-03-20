<?php

namespace App\Kafka\Events;

use App\Models\User;

class UserCreatedEvent implements Event
{
    public function __construct(public object $user)
    {
    }
}
