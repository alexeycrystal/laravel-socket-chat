<?php


namespace App\Modules\User\Repositories;


use App\GenericModels\User;
use App\Generics\Repositories\AbstractRepository;
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
}
