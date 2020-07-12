<?php


namespace App\Generics\Transformers;


use App\Entities\Response\Login\LoginResponseEntity;
use App\Entities\Response\Login\LoginResultEntity;

class BaseDataResponseTransformer
{
    public static function transform(LoginResultEntity $data): LoginResponseEntity
    {
        $entity = new LoginResponseEntity();

        $entity->data = $data;

        return $entity;
    }
}
