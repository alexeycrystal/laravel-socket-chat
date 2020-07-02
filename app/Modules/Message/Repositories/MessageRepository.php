<?php


namespace App\Modules\Message\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\Message\Models\Message;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class MessageRepository
 * @package App\Modules\Message\Repositories
 */
class MessageRepository extends AbstractRepository implements MessageRepositoryContract
{
    /**
     * @param int $chatId
     * @param array $params
     * @return Collection|null
     */
    public function getMessages(int $chatId, array $params): ?Collection
    {
        $query = DB::table('messages as message')
            ->join('user_settings as settings', function(Builder $query) use ($chatId) {
                $query->on('settings.user_id', '=', 'message.user_id')
                    ->where('message.chat_id', '=', $chatId);
            })
            ->select([
                'message.id',
                'message.chat_id',
                'message.user_id',
                'message.text',
                'message.created_at',
                'message.updated_at',
                'settings.avatar_path',
            ])
            ->selectRaw("count(message.id) over() as total_messages")
            ->orderBy('message.created_at', 'desc')
            ->take($params['take'])
            ->skip($params['skip']);

        $result = $query->get();

        if($result && $result->isNotEmpty())
            return $result;

        return null;
    }

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

    /**
     * @param int $chatId
     * @param int $messageId
     * @return \stdClass|null
     */
    public function selectRowNumberByTotalMessages(int $chatId, int $messageId): ?\stdClass
    {
        $subQuery = DB::table('messages')
            ->select([
                'id'
            ])
            ->selectRaw("row_number() over() as row_number")
            ->selectRaw("count(id) over() as total_count")
            ->where('chat_id', '=', $chatId)
            ->orderBy('created_at', 'asc');

        $query = DB::query()
            ->fromSub($subQuery, 'result')
            ->select([
                'row_number',
                'total_count',
            ])
            ->where('id', '=', $messageId);

        $result = $query->first();

        if($result)
            return $result;

        return null;
    }
}
