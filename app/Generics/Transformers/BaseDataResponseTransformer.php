<?php


namespace App\Generics\Transformers;


class BaseDataResponseTransformer
{
    public static function transform(array $data): array
    {
        return [
            'data' => $data
        ];
    }
}
