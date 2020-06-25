<?php


namespace App\Modules\Realtime\Repositories;


use App\Generics\Repositories\AbstractRepository;
use Illuminate\Support\Facades\Redis;

/**
 * Class UserRealtimeDependencyRedisRepository
 * @package App\Modules\Realtime\Repositories
 */
class UserRealtimeDependencyRedisRepository extends AbstractRepository implements UserRealtimeDependencyRepositoryContract
{
    /**
     * @var string
     */
    protected string $corePath = 'user_realtime_dependencies:status';

    /**
     * @param int $userId
     * @return array|null
     */
    public function get(int $userId): ?array
    {
        $keyPath = $this->corePath . ":" . $userId;

        $result = Redis::sInter($keyPath);

        if($result)
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param array $dependencyUsersIds
     * @return bool|null
     */
    public function storeUsersIdsToListen(int $userId, array $dependencyUsersIds): ?bool
    {
        $result = Redis::pipeline(function ($pipe) use ($userId, $dependencyUsersIds) {

            foreach ($dependencyUsersIds as $dependencyUserId) {

                $path = $this->corePath . ':listen:' . $dependencyUserId;

                $pipe->zAdd($path, $userId . ':' . $dependencyUserId);
            }
        });

        if($result)
            return true;

        return false;
    }

    /**
     * @param int $userId
     * @return bool|null
     */
    public function removeListenerFromGroups(int $userId): ?bool
    {
        $key = $this->corePath . ':listen';

        $result = Redis::rawCommand(
            'zRemRangeByLex', $key, '[' . $userId , '[' . $userId
        );

        if($result)
            return true;

        return false;

    }

    /**
     * @param int $userId
     * @return array|null
     */
    public function getAllListenersByUser(int $userId): ?array
    {
        $key = $this->corePath . ':listen';

        $result = Redis::zRangeByScore($key, $userId, $userId);

        if($result)
            return $result;

        return null;
    }
}
