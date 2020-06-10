<?php


namespace App\Modules\Message\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\Message\Models\Message;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param int $userId
     * @param int $messageId
     * @return bool|null
     */
    public function isExistsByUser(int $userId, int $messageId): ?bool
    {
        $result = DB::table('messages')
            ->where('id', '=', $messageId)
            ->where('user_id', '=', $userId)
            ->exists();

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $messageId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $messageId, array $payload): ?bool
    {
        $result = DB::table('messages')
            ->where('id', '=', $messageId)
            ->update($payload);

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $messageId
     * @return bool|null
     */
    public function delete(int $messageId): ?bool
    {
        $result = DB::table('messages')
            ->where('id', '=', $messageId)
            ->delete();

        if(isset($result))
            return $result;

        return null;
    }
}
