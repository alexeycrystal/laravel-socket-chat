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

    public static function transformPhotoSave(string $photoPath): array
    {
        return [
            'data' => [
                'result' => isset($photoPath),
                'path' => config('app.url') . $photoPath
            ]
        ];
    }
}
