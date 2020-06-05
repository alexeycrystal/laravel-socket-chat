<?php


namespace App\Modules\User\Repositories;


use App\GenericModels\User;

/**
 * Interface UserRepositoryContract
 * @package App\Modules\User\Repositories
 */
interface UserRepositoryContract
{
    /**
     * @param array $payload
     * @return User|null
     */
    public function create(array $payload): ?User;
}
