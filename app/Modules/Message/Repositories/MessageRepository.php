<?php


namespace App\Modules\Message\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\Message\Models\Message;

/**
 * Class MessageRepository
 * @package App\Modules\Message\Repositories
 */
class MessageRepository extends AbstractRepository implements MessageRepositoryContract
{
    /**
     * @param array $payload
     * @return Message|null
     */
    public function create(array $payload): ?Message
    {
        $result = Message::create($payload);

        if($result)
            return $result;

        return null;
    }
}
