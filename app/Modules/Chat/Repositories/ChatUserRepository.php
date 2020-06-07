<?php


namespace App\Modules\Chat\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\Chat\Models\ChatUser;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatUserRepository
 * @package App\Modules\Chat\Repositories
 */
class ChatUserRepository extends AbstractRepository implements ChatUserRepositoryContract
{
    /**
     * @param array $payload
     * @return bool|null
     */
    public function bulkInsert(array $payload): ?bool
    {
        $result = ChatUser::insert($payload);

        if(isset($result))
            return $result;

        return null;
    }
}
