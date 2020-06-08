<?php


namespace App\Modules\Chat\Services;


use App\Facades\RepositoryManager;
use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Repositories\ChatRepositoryContract;
use App\Modules\Chat\Repositories\ChatUserRepositoryContract;
use Illuminate\Support\Arr;

/**
 * Class ChatService
 * @package App\Modules\Chat\Services
 */
class ChatService extends AbstractService implements ChatServiceContract
{
    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var ChatRepositoryContract
     */
    protected ChatRepositoryContract $chatRepository;
    /**
     * @var ChatUserRepositoryContract
     */
    protected ChatUserRepositoryContract $chatUserRepository;

    /**
     * ChatService constructor.
     * @param AuthServiceContract $authService
     * @param ChatRepositoryContract $chatRepository
     * @param ChatUserRepositoryContract $chatUserRepository
     */
    public function __construct(AuthServiceContract $authService,
                                ChatRepositoryContract $chatRepository,
                                ChatUserRepositoryContract $chatUserRepository)
    {
        $this->authService = $authService;
        $this->chatRepository = $chatRepository;
        $this->chatUserRepository = $chatUserRepository;
    }

    /**
     * @param array $usersIds
     * @return array|null
     */
    public function createChatIfNotExists(array $usersIds): ?array
    {
        $loggedUser = $this->authService->getLoggedUser();

        $loggedUserId = $loggedUser->id;

        $usersIds[] = $loggedUserId;

        sort($usersIds);

        $existedChat = $this->chatUserRepository
            ->isAlreadyExists($loggedUserId, $usersIds);

        if($existedChat) {

            $chatId = $existedChat->chat_id;
        } else {

            $chat = $this->createChat($loggedUserId, $usersIds);

            $chatId = $chat
                ? $chat->id
                : null;
        }

        if(!$chatId) {

            $this->addError(
                504,
                'ChatService@createChat',
                'Some serious error occurs during the chat creation process.'
            );
            return null;
        }

        return [
            'chat_id' => $chatId,
            'chat_already_exists' => isset($existedChat),
        ];
    }

    /**
     * @param int $userOwnerId
     * @param array $usersIds
     * @return Chat|null
     */
    public function createChat(int $userOwnerId, array $usersIds): ?Chat
    {
        $chat = RepositoryManager::resolveTransactional(function() use ($userOwnerId, $usersIds) {

            $isConference = count($usersIds) > 2;

            $chatPayload = [
                'owner_user_id' => $userOwnerId,
                'is_conference' => $isConference,
            ];

            $chatCreated = $this->chatRepository
                ->create($chatPayload);

            if(!$chatCreated)
                return null;

            $chatUsersPayload = [];

            foreach($usersIds as $chatUserId)
                $chatUsersPayload[] = [
                    'chat_id' => $chatCreated->id,
                    'user_id' => $chatUserId,
                ];

            $usersAssignedToChat = $this->chatUserRepository
                ->bulkInsert($chatUsersPayload);

            if(!$usersAssignedToChat)
                return null;

            return $chatCreated;

        }, true);

        if($chat)
            return $chat;

        return null;
    }

    /**
     * @param array $usersIds
     * @return bool|null
     */
    public function isChatAlreadyAssigned(array $usersIds): ?bool
    {
        $this->chatUserRepository
            ->isAlreadyExists($usersIds);
    }
}
