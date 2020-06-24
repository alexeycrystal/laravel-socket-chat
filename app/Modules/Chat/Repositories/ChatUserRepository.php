<?php


namespace App\Modules\Chat\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\Chat\Models\ChatUser;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatUserRepository
 * @package App\Modules\Chat\Repositories
 */
class ChatUserRepository extends AbstractRepository implements ChatUserRepositoryContract
{
    /**
     * @param int $userId
     * @param int $chatId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $userId, int $chatId, array $payload): ?bool
    {
        $result = DB::table('chat_user')
            ->where('chat_id', '=', $chatId)
            ->where('user_id', '=', $userId)
            ->update($payload);

        if(isset($result))
            return $result;

        return null;
    }

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

    /**
     * @param int $userId
     * @param array $usersIds
     * @return \stdClass|null
     */
    public function isAlreadyExists(int $userId, array $usersIds): ?\stdClass
    {
        $strUserIds = implode(',', $usersIds);

        $withQuery = DB::table('chat_user')
            ->where('chat_user.user_id', '=', $userId)
            ->select([
                'chat_id'
            ]);

        $query = DB::table('result')
            ->withExpression('chats_by_user', $withQuery)
            ->fromSub(
                DB::table('chat_selections')
                    ->fromSub(function(Builder $query) {

                        $query->from('chats_by_user as logged_user_chats')
                            ->join('chat_user', 'chat_user.chat_id', '=', 'logged_user_chats.chat_id')
                            ->select([
                                'logged_user_chats.chat_id',
                                'chat_user.user_id',
                            ])
                            ->orderBy('user_id');
                    }, 'chat_selections')
                    ->selectRaw('distinct on(chat_id) chat_id')
                    ->selectRaw("
                        array_to_string(
                            array_sort(
                                array_agg(user_id) over(partition by chat_id)
                            )
                        , ',') as users_json")
            , 'result')
            ->whereRaw("users_json::varchar = '{$strUserIds}'::varchar")
            ->limit(1);

        $result = $query->first();

        if($result)
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param array $params
     * @return Collection|null
     */
    public function getAvailableChatsByUser(int $userId, array $params): ?Collection
    {
        $take = $params['take'];
        $skip = $params['skip'];

        $withQuery = DB::table('chats')
            ->join('chat_user', function(Builder $query) use ($userId) {

                $query->on('chats.id', '=', 'chat_user.chat_id')
                    ->where('chat_user.user_id', '=', $userId)
                    ->whereRaw('chat_user.is_visible is true');
            })
            ->select([
                'chat_id'
            ])
            ->selectRaw("count(\"chats\".\"id\") over() as total_chats")
            ->orderBy('chats.updated_at', 'desc')
            ->take($take)
            ->skip($skip);

        $query = DB::table('logged_user_chats')
            ->withExpression('logged_user_chats', $withQuery)
            ->join('chat_user', 'chat_user.chat_id', '=', 'logged_user_chats.chat_id')
            ->join('users as u', function(Builder $query) use ($userId) {

                $query->on('u.id', '=', 'chat_user.user_id')
                    ->where('u.id', '!=', $userId);
            })
            ->join('user_settings as settings', 'settings.user_id', '=', 'u.id')
            ->select([
                'logged_user_chats.total_chats',
                'logged_user_chats.chat_id as chat_id',
                'u.id as user_id',
                'u.name as user_name',
                'settings.avatar_path',
            ]);

        $result = $query->get();

        if($result && $result->isNotEmpty())
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param int $chatId
     * @return bool|null
     */
    public function isUserExistsByChat(int $userId, int $chatId): ?bool
    {
        $result = DB::table('chat_user')
            ->where('chat_id', '=', $chatId)
            ->where('user_id', '=', $userId)
            ->exists();

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $chatId
     * @param array|null $exceptUserIds
     * @return array|null
     */
    public function getUserIdsByChat(int $chatId, ?array $exceptUserIds = null): ?array
    {
        $query = DB::table('chat_user')
            ->where('chat_id', '=', $chatId)
            ->select([
                'user_id'
            ]);

        if($exceptUserIds)
            $query->whereNotIn('user_id', $exceptUserIds);

        $result = $query->get();

        if($result && $result->isNotEmpty())
            return $result->pluck('user_id')->values()->toArray();

        return null;
    }
}
