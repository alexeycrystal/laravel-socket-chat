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
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?User;

    /**
     * @param array $payload
     * @return User|null
     */
    public function create(array $payload): ?User;
}
