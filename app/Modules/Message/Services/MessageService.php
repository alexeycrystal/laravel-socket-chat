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
    public function getMessagesByChat(int $chatId, array $params): ?array
    {
        $perPage = $params['per_page'];

        if(isset($params['message_id'])) {

            $messageId = $params['message_id'];

            $result = $this->messageRepository
                ->selectRowNumberByTotalMessages($chatId, $messageId);

            if($result) {

                $page = $this->computePageNumberByParts(
                    $result->row_number,
                    $result->total_count,
                    $perPage
                );

                $params['page'] = $page;
            }
        } else {
            $page = $params['page'];
        }

        $payload = [
            'take' => $perPage,
            'skip' => $page > 1
                ? $perPage * ($page - 1)
                : 0,
        ];

        $result = $this->messageRepository
            ->getMessages($chatId, $payload);

        if($result)
            return [
                'result' => $result,
                'payload' => $params
            ];

        return null;
    }

    /**
     * Get page number for specific message
     *
     * @param int $rowNumber
     * @param int $totalMessagesCount
     * @param int $perPage
     * @return int
     */
    protected function computePageNumberByParts(int $rowNumber,
                                                int $totalMessagesCount,
                                                int $perPage): int
    {
        $parts = null;

        if($totalMessagesCount > $perPage) {
            $parts = ceil($totalMessagesCount / $perPage);
        } else if ($totalMessagesCount) {
            $parts = 1;
        }

        $result = 0;

        for($i = 1; $i <= $parts; ++$i) {

            $biggerPart = $i * $perPage;
            $smallerPart = $biggerPart - $perPage;

            if($rowNumber > $smallerPart
                && $rowNumber <= $biggerPart) {
                $result = $i;
                break;
            }
        }

        return $result;
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
