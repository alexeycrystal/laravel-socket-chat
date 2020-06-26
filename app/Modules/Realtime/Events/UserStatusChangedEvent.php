<?php


namespace App\Modules\Realtime\Events;


use App\Events\GenericMultiBroadcastEvent;

class UserStatusChangedEvent extends GenericMultiBroadcastEvent
{
    protected string $eventAlias = 'UserStatusChangedEvent';
}
