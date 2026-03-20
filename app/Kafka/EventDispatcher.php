<?php

namespace App\Kafka;

use App\Kafka\Events\Event;

class EventDispatcher
{
    public function __construct(
        private iterable $handlers
    )
    {
    }

    public function dispatch(Event $event): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($event)) {
                $handler->handle($event);
            }
        }
    }
}
