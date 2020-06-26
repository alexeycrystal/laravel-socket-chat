<?php


namespace App\Http\Middleware\Broadcast;


use App\Facades\BroadcastEventsFactory;
use App\Modules\Realtime\Repositories\UserRealtimeDependencyRepositoryContract;
use Closure;
use Illuminate\Support\Facades\Log;

class PostProcessingBroadcastMiddleware
{
    protected UserRealtimeDependencyRepositoryContract $userRealtimeDependencyRepository;

    public function __construct(UserRealtimeDependencyRepositoryContract $userRealtimeDependencyRepository)
    {
        $this->userRealtimeDependencyRepository = $userRealtimeDependencyRepository;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::debug('Trying to process: ' . json_encode(isset($request->postprocessing_broadcast_data)));

        if(isset($request->postprocessing_broadcast_data)) {

            $data = $request->postprocessing_broadcast_data;

            $eventName = $data['event'];
            $channelName = $data['channel_name'];
            $userId = $data['broadcaster_user_id'];
            $dataToPush = $data['data'];

            $listenersUsersIds = $this->userRealtimeDependencyRepository
                ->getAllListenersByUser($userId);

            Log::debug('Listeners found: ' . json_encode([$channelName, $listenersUsersIds, $dataToPush]));

            if(!empty($listenersUsersIds)) {

                $event = BroadcastEventsFactory::makeEvent($eventName,[$channelName, $listenersUsersIds, $dataToPush]);

                broadcast($event);
            }
        }
    }
}
