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

        return null;
    }
}
