<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractServiceContract;

/**
 * Interface UserProfileServiceContract
 * @package App\Modules\User\Services
 */
interface UserProfileServiceContract extends AbstractServiceContract
{
    /**
     * @return array|null
     */
    public function getUserProfileInfoByLoggedUser(): ?array;

    /**
     * @param string $password
     * @return array|null
     */
    public function changePassword(string $password): ?array;
}
