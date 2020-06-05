<?php


namespace App\Modules\User\Repositories;


use App\Modules\User\Models\UserSettings;

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
}
