<?php


namespace App\Modules\Auth\Transformers;


use App\Entities\Response\Login\LoginResponseEntity;
use App\Entities\Response\Login\LoginResultEntity;
use App\Generics\Transformers\AbstractTransformer;

class LoginTransformer extends AbstractTransformer
{
    public static function transform(LoginResultEntity $data): LoginResponseEntity
    {
        $entity = new LoginResponseEntity();

        $entity->data = $data;

        return $entity;
    }
}
