<?php


namespace App\Modules\Realtime\Transformers;


class UserRealtimeDependencyTransformer
{
    public static function transformStoreResult(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }

    public static function transformDestroyResult(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
