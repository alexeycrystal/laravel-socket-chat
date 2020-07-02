<?php


namespace App\Modules\Chat\Services;


use App\Facades\RepositoryManager;
use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Repositories\ChatRepositoryContract;
use App\Modules\Chat\Repositories\ChatUserRepositoryContract;
use App\Modules\Message\Repositories\MessageRepositoryContract;
use App\Modules\User\Repositories\UserCacheRepositoryContract;
use App\Modules\User\Services\UserContactsServiceContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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
     * @var UserContactsServiceContract
     */
    protected UserContactsServiceContract $userContactsService;

    /**
     * @var UserCacheRepositoryContract
     */
    protected UserCacheRepositoryContract $userCacheRepository;

    protected MessageRepositoryContract $messageRepository;

    /**
     * ChatService constructor.
     * @param AuthServiceContract $authService
     * @param ChatRepositoryContract $chatRepository
     * @param ChatUserRepositoryContract $chatUserRepository
     * @param UserContactsServiceContract $userContactsService
     * @param UserCacheRepositoryContract $userCacheRepository
     * @param MessageRepositoryContract $messageRepository
     */
    public function __construct(AuthServiceContract $authService,
                                ChatRepositoryContract $chatRepository,
                                ChatUserRepositoryContract $chatUserRepository,
                                UserContactsServiceContract $userContactsService,
                                UserCacheRepositoryContract $userCacheRepository,
                                MessageRepositoryContract $messageRepository)
    {
        $this->authService = $authService;
        $this->chatRepository = $chatRepository;
        $this->chatUserRepository = $chatUserRepository;
        $this->userContactsService = $userContactsService;
        $this->userCacheRepository = $userCacheRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param array $payload
     * @return array|null
     */
    public function getChats(array $payload): ?array
    {
        $user = $this->authService->getLoggedUser();

        $params = [
            'take' => $payload['per_page'],
            'skip' => $payload['page'] > 1
                ? $payload['per_page'] * $payload['page']
                : 0,
        ];

        if (isset($payload['filter']))
            $params['filter'] = $payload['filter'];

        if (!isset($params['filter']))
            $result = $this->chatUserRepository
                ->getAvailableChatsByUser($user->id, $params);
        else
            $result = $this->chatUserRepository
                ->getAvailableChatsByFilter($user->id, $params);

        if ($result) {

            $userIds = $result->pluck('user_id')
                ->values()
                ->toArray();

            $statuses = $this->userCacheRepository
                ->getStatusesByUserIds($userIds);

            return [
                'result' => $result,
                'statuses' => $statuses,
                'users_ids' => $userIds,
                'is_filterable' => isset($params['filter'])
            ];
        }

        return null;
    }

    /**
     * @param array $usersIds
     * @return array|null
     */
    public function createChatIfNotExists(array $usersIds): ?array
    {
        $loggedUser = $this->authService->getLoggedUser();

        $loggedUserId = $loggedUser->id;

        $existedChatId = $this->isChatAlreadyAssigned($loggedUserId, $usersIds);

        if ($existedChatId) {

            $chatId = $existedChatId;
        } else {

            $chat = $this->createChat($loggedUserId, $usersIds);

            $chatId = $chat
                ? $chat->id
                : null;
        }

        if (!$chatId) {

            $this->addError(
                504,
                'ChatService@createChatIfNotExists',
                'Some serious error occurs during the chat creation process.'
            );
            return null;
        }

        return [
            'chat_id' => $chatId,
            'chat_already_exists' => isset($existedChatId),
        ];
    }

    /**
     * @param int $loggedUserId
     * @param array $usersIds
     * @return int|null
     */
    public function isChatAlreadyAssigned(int $loggedUserId, array $usersIds): ?int
    {
        $validationUsersIds = $usersIds;
        $validationUsersIds[] = $loggedUserId;

        sort($validationUsersIds);

        $existedChat = $this->chatUserRepository
            ->isAlreadyExists($loggedUserId, $validationUsersIds);

        if ($existedChat)
            return $existedChat->chat_id;

        return null;
    }

    /**
     * @param int $userOwnerId
     * @param array $usersIds
     * @return Chat|null
     */
    public function createChat(int $userOwnerId, array $usersIds): ?Chat
    {
        $chat = RepositoryManager::resolveTransactional(function () use ($userOwnerId, $usersIds) {

            $isConference = count($usersIds) > 2;

            $chatPayload = [
                'owner_user_id' => $userOwnerId,
                'is_conference' => $isConference,
            ];

            $chatCreated = $this->chatRepository
                ->create($chatPayload);

            if (!$chatCreated)
                return null;

            $chatUsersPayload = [];

            $chatUsersIds = $usersIds;
            $chatUsersIds[] = $userOwnerId;

            foreach ($chatUsersIds as $chatUserId)
                $chatUsersPayload[] = [
                    'chat_id' => $chatCreated->id,
                    'user_id' => $chatUserId,
                ];

            $usersAssignedToChat = $this->chatUserRepository
                ->bulkInsert($chatUsersPayload);

            if (!$usersAssignedToChat)
                return null;

            return $chatCreated;

        }, true);

        if ($chat)
            return $chat;

        return null;
    }

    /**
     * @param int $chatId
     * @return bool|null
     */
    public function hideChatAndClearHistory(int $chatId): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->chatUserRepository
            ->update($user->id, $chatId, ['is_visible' => false]);

        if (isset($result))
            return $result;

        $this->addError(
            504,
            'ChatService@hideChatAndClearHistory',
            'Some serious error occurs during the chat update process.'
        );

        return null;
    }

    /**
     * @param int $userId
     * @param int $chatId
     * @return bool|null
     */
    public function isUserExistsByChat(int $userId, int $chatId): ?bool
    {
        $result = $this->chatUserRepository
            ->isUserExistsByChat($userId, $chatId);

        if (isset($result))
            return $result;

        $this->addError(
            504,
            'ChatService@isUserExistsByChat',
            'Some serious error occurs during the chat user validation process.'
        );
        return null;
    }

    /**
     * @param int $userId
     * @param array $chatIDs
     * @return bool|null
     */
    public function isUserHasAccessToChats(int $userId, array $chatIDs): ?bool
    {
        $result = $this->chatUserRepository
            ->isUserHasAccessToChats($userId, $chatIDs);

        if (isset($result))
            return $result;

        $this->addError(
            504,
            'ChatService@isUserHasAccessToChats',
            'Some serious error occurs during the chat user validation process.'
        );
        return null;
    }
}
