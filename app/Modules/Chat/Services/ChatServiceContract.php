<?php


namespace App\Modules\Chat\Services;


use App\Generics\Services\AbstractServiceContract;
use App\Modules\Chat\Models\Chat;
use Illuminate\Support\Collection;

/**
 * Interface ChatServiceContract
 * @package App\Modules\Chat\Services
 */
interface ChatServiceContract extends AbstractServiceContract
{
    /**
     * @param array $params
     * @return array|null
     */
    public function getChats(array $params): ?array;

    /**
     * @param array $usersIds
     * @return array|null
     */
    public function createChatIfNotExists(array $usersIds): ?array;

    /**
     * @param int $loggedUserId
     * @param array $usersIds
     * @return int|null
     */
    public function isChatAlreadyAssigned(int $loggedUserId, array $usersIds): ?int;

    /**
     * @param int $userOwnerId
     * @param array $usersIds
     * @return Chat|null
     */
    public function createChat(int $userOwnerId, array $usersIds): ?Chat;

    /**
     * @param int $chatId
     * @return bool|null
     */
    public function hideChatAndClearHistory(int $chatId): ?bool;

    /**
     * @param int $userId
     * @param int $chatId
     * @return bool|null
     */
    public function isUserExistsByChat(int $userId, int $chatId): ?bool;

    /**
     * @param int $userId
     * @param array $chatIDs
     * @return bool|null
     */
    public function isUserHasAccessToChats(int $userId, array $chatIDs): ?bool;

    /**
     * @param int $userId
     * @return \stdClass|null
     */
    public function getChatUserMetaInfo(int $userId): ?\stdClass;
}
