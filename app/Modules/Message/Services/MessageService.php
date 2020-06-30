<?php


namespace App\Modules\Message\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Repositories\ChatUserRepositoryContract;
use App\Modules\Message\Events\ChatMessageSentEvent;
use App\Modules\Message\Models\Message;
use App\Modules\Message\Repositories\MessageRepositoryContract;
use App\Modules\Message\Transformers\MessageTransformer;
use App\Modules\User\Repositories\UserSettingsRepositoryContract;
use Illuminate\Support\Collection;

/**
 * Class MessageService
 * @package App\Modules\Message\Services
 */
class MessageService extends AbstractService implements MessageServiceContract
{
    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var MessageRepositoryContract
     */
    protected MessageRepositoryContract $messageRepository;
    /**
     * @var UserSettingsRepositoryContract
     */
    protected UserSettingsRepositoryContract $userSettingsRepository;
    /**
     * @var ChatUserRepositoryContract
     */
    protected ChatUserRepositoryContract $chatUserRepository;

    /**
     * MessageService constructor.
     * @param AuthServiceContract $authService
     * @param MessageRepositoryContract $messageRepository
     * @param UserSettingsRepositoryContract $userSettingsRepository
     * @param ChatUserRepositoryContract $chatUserRepository
     */
    public function __construct(AuthServiceContract $authService,
                                MessageRepositoryContract $messageRepository,
                                UserSettingsRepositoryContract $userSettingsRepository,
                                ChatUserRepositoryContract $chatUserRepository)
    {
        $this->authService = $authService;
        $this->messageRepository = $messageRepository;
        $this->userSettingsRepository = $userSettingsRepository;
        $this->chatUserRepository = $chatUserRepository;
    }

    /**
     * @param int $chatId
     * @param array $params
     * @return Collection|null
     */
    public function getMessagesByChat(int $chatId, array $params): ?Collection
    {
        $payload = [
            'take' => $params['per_page'],
            'skip' => $params['page'] > 1
                ? $params['per_page'] * ($params['page'] - 1)
                : 0,
        ];

        $result = $this->messageRepository
            ->getMessages($chatId, $payload);

        if($result)
            return $result;

        return null;
    }

    /**
     * @param array $payload
     * @return Message|null
     */
    public function createByLoggedUser(array $payload): ?Message
    {
        $user = $this->authService->getLoggedUser();

        $chatId = $payload['chat_id'];

        $data = [
            'user_id' => $user->id,
            'chat_id' => $chatId,
            'text' => $payload['text'],
        ];

        $message = $this->messageRepository
            ->create($data);

        if($message) {

            $message->avatar = $this->userSettingsRepository
                ->getAvatarPathByUserId($user->id);

            $userIdsByChat = $this->chatUserRepository
                ->getUserIdsByChat($chatId, [$user->id]);

            broadcast(
                new ChatMessageSentEvent(
                    'chat.user',
                    $userIdsByChat,
                    MessageTransformer::transformMessageStore($message)
                )
            );

            return $message;
        }

        $this->addError(
            504,
            'MessageService@createByLoggedUser',
            'Some serious error occurs during the message creation process.'
        );
        return null;
    }

    /**
     * @param int $userId
     * @param int $messageId
     * @return bool|null
     */
    public function isMessageExistsByUser(int $userId, int $messageId): ?bool
    {
        $result = $this->messageRepository
            ->isExistsByUser($userId, $messageId);

        if(isset($result))
            return $result;

        $this->addError(
            504,
            'MessageService@isMessageExistsByUser',
            'Some serious error occurs during the chat message validation process.'
        );
        return null;
    }

    /**
     * @param int $messageId
     * @param array $payload
     * @return bool|null
     */
    public function updateMessage(int $messageId, array $payload): ?bool
    {
        $data = [
            'text' => $payload['text'],
        ];

        $result = $this->messageRepository
            ->update($messageId, $data);

        if(isset($result))
            return $result;

        $this->addError(
            504,
            'MessageService@updateMessage',
            'Some serious error occurs during the chat message validation process.'
        );
        return null;
    }

    /**
     * @param int $messageId
     * @return bool|null
     */
    public function deleteMessage(int $messageId): ?bool
    {
        $result = $this->messageRepository
            ->delete($messageId);

        if(isset($result))
            return $result;

        $this->addError(
            504,
            'MessageService@deleteMessage',
            'Some serious error occurs during the message deletion process.'
        );
        return null;
    }
}
