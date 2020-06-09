<?php


namespace App\Modules\Chat\Repositories;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Interface ChatUserRepositoryContract
 * @package App\Modules\Chat\Repositories
 */
interface ChatUserRepositoryContract
{
    /**
     * @param int $userId
     * @param int $chatId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $userId, int $chatId, array $payload): ?bool;

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

    /**
     * @param int $userId
     * @param array $params
     * @return Collection|null
     */
    public function getAvailableChatsByUser(int $userId, array $params): ?Collection;

    /**
     * @param int $userId
     * @param int $chatId
     * @return bool|null
     */
    public function isUserExistsByChat(int $userId, int $chatId): ?bool;
}
