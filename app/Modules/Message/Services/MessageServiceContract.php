<?php


namespace App\Modules\Message\Services;


use App\Generics\Services\AbstractServiceContract;
use Illuminate\Support\Collection;

/**
 * Interface MessageServiceContract
 * @package App\Modules\Message\Services
 */
interface MessageServiceContract extends AbstractServiceContract
{
    /**
     * @param int $chatId
     * @param array $params
     * @return Collection|null
     */
    public function getMessagesByChat(int $chatId, array $params): ?Collection;

    /**
     * @param array $payload
     * @return int|null
     */
    public function createByLoggedUser(array $payload): ?int;

    /**
     * @param int $userId
     * @param int $messageId
     * @return bool|null
     */
    public function isMessageExistsByUser(int $userId, int $messageId): ?bool;

    /**
     * @param int $messageId
     * @param array $payload
     * @return bool|null
     */
    public function updateMessage(int $messageId, array $payload): ?bool;

    /**
     * @param int $messageId
     * @return bool|null
     */
    public function deleteMessage(int $messageId): ?bool;
}
