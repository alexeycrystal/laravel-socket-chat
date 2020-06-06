<?php


namespace App\Modules\User\Transformers;



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
