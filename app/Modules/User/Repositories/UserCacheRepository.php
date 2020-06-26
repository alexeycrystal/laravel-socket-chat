<?php


namespace App\Modules\User\Repositories;


use App\Generics\Repositories\AbstractRepository;
use Illuminate\Support\Facades\Redis;

/**
 * Class UserCacheRepository
 * @package App\Modules\User\Repositories
 */
class UserCacheRepository extends AbstractRepository implements UserCacheRepositoryContract
{
    /**
     * @var string
     */
    protected string $rootPath = 'user:profile';

    /**
     * @param int $userId
     * @param string $status
     * @return bool|null
     */
    public function updateStatus(int $userId, string $status): ?bool
    {
        $path = $this->rootPath . ':' . $userId . ':status';

        $result = Redis::set($path, $status);

        if($result)
            return true;

        return false;
    }

    /**
     * @param array $userIds
     * @return array|null
     */
    public function getStatusesByUserIds(array $userIds): ?array
    {
        $keys = [];

        foreach($userIds as $userId)
            $keys[] = $path = $this->rootPath . ':' . $userId . ':status';

        $result = Redis::mget($keys);

        if($result)
            return $result;

        return null;
    }
}
