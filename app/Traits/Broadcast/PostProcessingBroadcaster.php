<?php


namespace App\Traits\Broadcast;


/**
 * Trait PostProcessingBroadcaster
 * @package App\Traits\Broadcast
 */
trait PostProcessingBroadcaster
{
    /**
     * @param string $eventName
     * @param string $channelName
     * @param int $broadcasterUserId
     * @param array $data
     */
    public function preparePostBroadcastData(string $eventName,
                                             string $channelName,
                                             int $broadcasterUserId,
                                             array $data): void
    {
        request()->merge([
            'postprocessing_broadcast_data' => [
                'event' => $eventName,
                'channel_name' => $channelName,
                'broadcaster_user_id' => $broadcasterUserId,
                'data' => $data,
            ]
        ]);
    }
}
