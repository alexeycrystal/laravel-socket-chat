<?php


namespace App\Modules\User\Transformers\UserContact;


class UserContactTransformer
{
    public static function transformContactStore(bool $result): array
    {
        return [
            'data' => [
                'result' => $result
            ]
        ];
    }
}
