<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed makeEvent(string $event, array $args)
 **/
class BroadcastEventsFactory extends Facade
{
    protected static function getFacadeAccessor() { return 'BroadcastEventsFactory'; }
}
