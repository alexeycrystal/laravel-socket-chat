<?php


namespace App\Modules\User\Transformers;


class UserPasswordChangeTransformer
{
    public static function transform(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ],
        ];
    }
}
