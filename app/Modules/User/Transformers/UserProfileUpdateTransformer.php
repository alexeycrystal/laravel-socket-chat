<?php


namespace App\Modules\User\Transformers;


class UserProfileUpdateTransformer
{
    public static function transform(bool $result)
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
