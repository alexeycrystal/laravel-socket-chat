<?php


namespace App\Events;


use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class GenericMultiBroadcastEvent
 * @package App\Events
 */
abstract class GenericMultiBroadcastEvent implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var string
     */
    protected string $eventAlias;
    /**
     * @var string
     */
    protected string $channelName;
    /**
     * @var array
     */
    protected array $userIds;
    /**
     * @var array
     */
    protected array $data;

    /**
     * Create a new event instance.
     *
     * @param string $channelName
     * @param array $userIds
     * @param array $data
     */
    public function __construct(string $channelName,
                                array $userIds,
                                array $data)
    {
        $this->channelName = $channelName;
        $this->userIds = $userIds;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];

        $channelName = $this->channelName;

        foreach($this->userIds as $userId)
            $channels[] = new PresenceChannel($channelName . '.' . $userId);

        return $channels;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return $this->eventAlias ?? 'ConcreteMultiBroadcastEvent';
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}
