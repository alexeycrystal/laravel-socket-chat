<?php


namespace App\Modules\Chat\Repositories;


use App\Generics\Services\AbstractServiceContract;

/**
 * Interface ChatUserRepositoryContract
 * @package App\Modules\Chat\Repositories
 */
interface ChatUserRepositoryContract
{
    /**
     * @param array $payload
     * @return bool|null
     */
    public function bulkInsert(array $payload): ?bool;

    /**
     * @param int $userId
     * @param array $usersIds
     * @return \stdClass|null
     */
    public function isAlreadyExists(int $userId, array $usersIds): ?\stdClass;
}
