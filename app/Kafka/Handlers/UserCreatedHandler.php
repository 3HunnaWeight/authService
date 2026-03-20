<?php

namespace App\Kafka\Handlers;

use App\Kafka\Events\Event;
use App\Kafka\Events\UserCreatedEvent;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class UserCreatedHandler implements EventHandler
{
    public function supports(object $event): bool
    {
        return $event instanceof UserCreatedEvent;
    }

    public function handle(Event $event): void
    {
        Kafka::publish()
            ->onTopic('users.events')
            ->withMessage(new Message(
                headers: ['event' => 'UserCreated'],
                body: [
                    'user_id' => $event->user->id,
                    'name' => $event->user->name,
                    'email' => $event->user->email,
                ]
            ))
            ->send();
    }
}
