<?php


namespace App\Modules\User\Transformers;


use App\Modules\User\Models\UserSettings;

class UserProfileTransformer
{
    public static function transform(\stdClass $userSettings): array
    {
        return [
            'data' => [
                'name' => $userSettings->name,
                'nickname' => $userSettings->nickname ?? '',
                'timezone' => $userSettings->timezone,
                'phone' => $userSettings->phone ?? '',
                'lang' => $userSettings->lang,
            ],
        ];
    }
}
