<?php


namespace App\Modules\Realtime\Repositories;


/**
 * Interface UserRealtimeDependencyRepositoryContract
 * @package App\Modules\Realtime\Repositories
 */
interface UserRealtimeDependencyRepositoryContract
{
    /**
     * @param int $userId
     * @return array|null
     */
    public function get(int $userId): ?array;

    /**
     * @param int $userId
     * @param array $dependencyUsersIds
     * @return bool|null
     */
    public function storeUsersIdsToListen(int $userId, array $dependencyUsersIds): ?bool;

    /**
     * @param int $userId
     * @return bool|null
     */
    public function removeListenerFromGroups(int $userId): ?bool;

    /**
     * @param int $userId
     * @return array|null
     */
    public function getAllListenersByUser(int $userId): ?array;
}
