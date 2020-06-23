<?php


namespace App\Modules\Message\Repositories;


use App\Modules\Message\Models\Message;
use Illuminate\Support\Collection;

/**
 * Interface MessageRepositoryContract
 * @package App\Modules\Message\Repositories
 */
interface MessageRepositoryContract
{
    /**
     * @param int $chatId
     * @param array $params
     * @return Collection|null
     */
    public function getMessages(int $chatId, array $params): ?Collection;

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
