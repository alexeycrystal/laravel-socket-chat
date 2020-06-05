<?php


namespace App\Modules\User\Repositories;


use App\Generics\Repositories\AbstractRepository;
use App\Modules\User\Models\UserSettings;
use Illuminate\Support\Facades\DB;
use stdClass;

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

    /**
     * @param int $userId
     * @return stdClass|null
     */
    public function get(int $userId): ?\stdClass
    {
        $query = DB::table('users as usr')
            ->join('user_settings as profile', 'profile.user_id', '=', 'usr.id')
            ->where('usr.id', '=', $userId)
            ->select([
                'usr.name',

                'profile.nickname',
                'profile.timezone',
                'profile.phone',
                'profile.lang',
            ]);

        $result = $query->first();

        if($result)
            return $result;

        return null;
    }
}
