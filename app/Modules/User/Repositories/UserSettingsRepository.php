<?php


namespace App\Modules\User\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\User\Models\UserSettings;

/**
 * Class UserSettingsRepository
 * @package App\Modules\User\Repositories
 */
class UserSettingsRepository extends AbstractRepository implements UserSettingsRepositoryContract
{
    /**
     * @param array $payload
     * @return UserSettings|null
     */
    public function create(array $payload): ?UserSettings
    {
        $created = UserSettings::create($payload);

        if($created)
            return $created;

        return null;
    }
}
