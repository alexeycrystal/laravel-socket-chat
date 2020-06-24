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
                'profile.avatar_path',
            ]);

        $result = $query->first();

        if($result)
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param string $nickname
     * @return bool|null
     */
    public function isNicknameAlreadyTaken(int $userId, string $nickname): ?bool
    {
        $result = DB::table('user_settings')
            ->where('user_id', '!=', $userId)
            ->where('nickname', '=', $nickname)
            ->exists();

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $userId, array $payload): ?bool
    {
        $result = DB::table('user_settings')
            ->where('user_id', '=', $userId)
            ->update($payload);

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @return string|null
     */
    public function getAvatarPathByUserId(int $userId): ?string
    {
        $query = DB::table('user_settings')
            ->where('user_id', '=', $userId)
            ->select(['avatar_path']);

        $result = $query->value('avatar_path');

        if($result)
            return $result;

        return null;
    }
}
