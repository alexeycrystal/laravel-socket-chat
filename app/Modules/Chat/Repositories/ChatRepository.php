<?php


namespace App\Modules\Chat\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\Chat\Models\Chat;

/**
 * Class ChatRepository
 * @package App\Modules\Chat\Repositories
 */
class ChatRepository extends AbstractRepository implements ChatRepositoryContract
{
    /**
     * @param array $payload
     * @return Chat|null
     */
    public function create(array $payload): ?Chat
    {
        $result = Chat::create($payload);

        if($result)
            return $result;

        return null;
    }
}
