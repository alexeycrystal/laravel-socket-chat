<?php


namespace App\Modules\User\Repositories;


use App\GenericModels\User;
use App\Generics\Repositories\AbstractRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Modules\User\Repositories
 */
class UserRepository extends AbstractRepository implements UserRepositoryContract
{
    /**
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?User
    {
        $user = User::where('id', '=', $id)
            ->first();

        if($user)
            return $user;

        return null;
    }

    /**
     * @param array $payload
     * @return User|null
     */
    public function create(array $payload): ?User
    {
        $created = User::create($payload);

        if($created)
            return $created;

        return null;
    }

    /**
     * @param int $userId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $userId, array $payload): ?bool
    {
        $result = DB::table('users')
            ->where('id', '=', $userId)
            ->update($payload);

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param array $userIds
     * @return bool|null
     */
    public function isUsersListExisted(array $userIds): ?bool
    {
        $query = DB::table('users')
            ->whereIn('id', $userIds)
            ->selectRaw('count(id) over() as total_count')
            ->limit(1);

        $result = $query->value('total_count');

        if($result)
            return $result === count($userIds);

        return null;
    }

    public function getUserMetaInfo(int $userId): ?\stdClass
    {
        $query = DB::table('users as user')
            ->join('user_settings as settings', function(Builder $query) use ($userId) {

                $query->on('user.id', '=', 'settings.user_id')
                    ->where('user.id', '=', $userId);
            })
            ->select([
                'user.id as user_id',
                'user.name as name',
                'settings.nickname as nickname',
                'settings.avatar_path as avatar',
            ]);

        $result = $query->first();

        if($result)
            return $result;

        return null;
    }
}
