<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractServiceContract;
use stdClass;

/**
 * Interface UserProfileServiceContract
 * @package App\Modules\User\Services
 */
interface UserProfileServiceContract extends AbstractServiceContract
{
    /**
     * @return stdClass|null
     */
    public function getUserProfileInfoByLoggedUser(): ?\stdClass;

    /**
     * @param string $password
     * @return bool|null
     */
    public function changePassword(string $password): ?bool;

    /**
     * @param array $payload
     * @return bool|null
     */
    public function updateProfileSettings(array $payload): ?bool;

    /**
     * @param int $userId
     * @param string $nickname
     * @return bool|null
     */
    public function isNicknameAlreadyTaken(int $userId, string $nickname): ?bool;

    /**
     * @param string $status
     * @return bool|null
     */
    public function changeLoggedUserStatus(string $status): ?bool;
}
