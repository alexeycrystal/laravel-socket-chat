<?php


namespace App\Modules\User\Repositories;


/**
 * Interface UserCacheRepositoryContract
 * @package App\Modules\User\Repositories
 */
interface UserCacheRepositoryContract
{
    /**
     * @param int $userId
     * @param string $status
     * @return bool|null
     */
    public function updateStatus(int $userId, string $status): ?bool;

    /**
     * @param array $userIds
     * @return array|null
     */
    public function getStatusesByUserIds(array $userIds): ?array;
}
