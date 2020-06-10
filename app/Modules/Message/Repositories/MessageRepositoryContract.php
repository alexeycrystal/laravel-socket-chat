<?php


namespace App\Modules\Message\Repositories;


use App\Modules\Message\Models\Message;

/**
 * Interface MessageRepositoryContract
 * @package App\Modules\Message\Repositories
 */
interface MessageRepositoryContract
{
    /**
     * @param array $payload
     * @return Message|null
     */
    public function create(array $payload): ?Message;

    /**
     * @param int $userId
     * @param int $messageId
     * @return bool|null
     */
    public function isExistsByUser(int $userId, int $messageId): ?bool;

    /**
     * @param int $messageId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $messageId, array $payload): ?bool;

    /**
     * @param int $messageId
     * @return bool|null
     */
    public function delete(int $messageId): ?bool;
}
