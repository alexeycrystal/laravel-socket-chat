<?php


namespace App\Factory\Events;


use ReflectionClass;

class BroadcastEventsFactory
{
    protected array $broadcastingEvents = [
        'ChatMessageSentEvent' => \App\Modules\Message\Events\ChatMessageSentEvent::class,
        'UserStatusChangedEvent' => \App\Modules\Realtime\Events\UserStatusChangedEvent::class,
    ];

    /**
     * @param string $event
     * @param array $args
     * @return object|null
     * @throws \ReflectionException
     */
    public function makeEvent(string $event, array $args): ?object
    {
        if(isset($this->broadcastingEvents[$event])) {

            return (new ReflectionClass($this->broadcastingEvents[$event]))
                ->newInstanceArgs($args);
        }

        return null;
    }
}
