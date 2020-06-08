<?php


namespace App\Modules\Chat\Services;


use App\Generics\Services\AbstractService;
use App\Generics\Services\AbstractServiceContract;
use App\Modules\Chat\Models\Chat;

/**
 * Interface ChatServiceContract
 * @package App\Modules\Chat\Services
 */
interface ChatServiceContract extends AbstractServiceContract
{
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
}
