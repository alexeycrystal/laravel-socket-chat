<?php


namespace App\Modules\User\Repositories;


use App\GenericModels\User;
use App\Generics\Repositories\AbstractRepository;

/**
 * Class UserRepository
 * @package App\Modules\User\Repositories
 */
class UserRepository extends AbstractRepository implements UserRepositoryContract
{
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
}
