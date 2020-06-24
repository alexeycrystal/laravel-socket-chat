<?php


namespace App\Modules\User\Repositories;


use App\Modules\User\Models\UserSettings;
use stdClass;

/**
 * Interface UserSettingsRepositoryContract
 * @package App\Modules\User\Repositories
 */
interface UserSettingsRepositoryContract
{
    /**
     * @param array $payload
     * @return UserSettings|null
     */
    public function create(array $payload): ?UserSettings;

    /**
     * @param int $userId
     * @return stdClass|null
     */
    public function get(int $userId): ?\stdClass;

    /**
     * @param int $userId
     * @param string $nickname
     * @return bool|null
     */
    public function isNicknameAlreadyTaken(int $userId, string $nickname): ?bool;

    /**
     * @param int $userId
     * @param array $payload
     * @return bool|null
     */
    public function update (int $userId, array $payload): ?bool;

    /**
     * @param int $userId
     * @return string|null
     */
    public function getAvatarPathByUserId(int $userId): ?string;
}
