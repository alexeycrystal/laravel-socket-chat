<?php


namespace App\Modules\Message\Events;


use App\Events\GenericMultiBroadcastEvent;

class ChatMessageSentEvent extends GenericMultiBroadcastEvent
{
    protected string $eventAlias = 'ChatMessageSent';
}
