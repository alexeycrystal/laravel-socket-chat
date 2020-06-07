<?php


namespace App\Modules\Chat\Services;


use App\Facades\RepositoryManager;
use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Repositories\ChatRepositoryContract;
use App\Modules\Chat\Repositories\ChatUserRepositoryContract;

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
     * @param array $payload
     * @return array|null
     */
    public function createChat(array $payload): ?array
    {
        $chat = RepositoryManager::resolveTransactional(function() use ($payload) {

            $usersIds = $payload['users_ids'];

            $isConference = count($usersIds) > 1;

            $loggedUser = $this->authService->getLoggedUser();

            $chatPayload = [
                'owner_user_id' => $loggedUser->id,
                'is_conference' => $isConference,
            ];

            $chatCreated = $this->chatRepository
                ->create($chatPayload);

            if(!$chatCreated)
                return null;

            $chatUsersPayload = [];

            $usersIds[] = $loggedUser->id;
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

        if(!$chat) {

            $this->addError(
                504,
                'ChatService@createChat',
                'Some serious error occurs during the chat creation process.'
            );
            return null;
        }

        return [
            'chat_id' => $chat->id,
        ];
    }
}
