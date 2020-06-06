<?php


namespace App\Modules\User\Services;


/**
 * Interface UserProfileServiceContract
 * @package App\Modules\User\Services
 */
interface UserProfileServiceContract
{
    /**
     * @return array|null
     */
    public function getUserProfileInfoByLoggedUser(): ?array;

    /**
     * @param array $payload
     * @return array|null
     */
    public function changePassword(array $payload): ?array;
}
