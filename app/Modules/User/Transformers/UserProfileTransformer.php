<?php


namespace App\Modules\User\Transformers;



class UserProfileTransformer
{
    public static function transform(\stdClass $userSettings): array
    {
        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        return [
            'data' => [
                'name' => $userSettings->name,
                'nickname' => $userSettings->nickname ?? '',
                'timezone' => $userSettings->timezone,
                'phone' => $userSettings->phone ?? '',
                'lang' => $userSettings->lang,
                'avatar' => $userSettings->avatar_path ?? $defaultAvatarUrl,
            ],
        ];
    }
}
