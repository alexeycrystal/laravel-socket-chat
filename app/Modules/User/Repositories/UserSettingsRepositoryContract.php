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
}
