<?php


namespace App\Modules\Message\Services;


use App\Generics\Services\AbstractServiceContract;

/**
 * Interface MessageServiceContract
 * @package App\Modules\Message\Services
 */
interface MessageServiceContract extends AbstractServiceContract
{
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
