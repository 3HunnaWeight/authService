<?php

namespace App\Kafka\Handlers;

use App\Kafka\Events\Event;

interface EventHandler
{
    public function supports(object $event): bool;

    public function handle(Event $event): void;
}
