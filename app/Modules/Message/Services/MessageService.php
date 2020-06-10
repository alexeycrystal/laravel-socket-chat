<?php


namespace App\Modules\Message\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Message\Repositories\MessageRepositoryContract;

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
     * MessageService constructor.
     * @param AuthServiceContract $authService
     * @param MessageRepositoryContract $messageRepository
     */
    public function __construct(AuthServiceContract $authService,
                                MessageRepositoryContract $messageRepository)
    {
        $this->authService = $authService;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param array $payload
     * @return int|null
     */
    public function createByLoggedUser(array $payload): ?int
    {
        $user = $this->authService->getLoggedUser();

        $data = [
            'user_id' => $user->id,
            'chat_id' => $payload['chat_id'],
            'text' => $payload['text'],
        ];

        $message = $this->messageRepository
            ->create($data);

        if($message)
            return $message->id;

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
}
