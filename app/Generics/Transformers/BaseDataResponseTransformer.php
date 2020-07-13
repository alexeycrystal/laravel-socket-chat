<?php


namespace App\Generics\Transformers;


use App\Entities\Response\Registration\RegistrationResponseEntity;
use App\Entities\Response\Registration\RegistrationResultEntity;

class BaseDataResponseTransformer
{
    public static function transform(RegistrationResultEntity $data): RegistrationResponseEntity
    {
        $entity = new RegistrationResponseEntity();

        $entity->data = $data;

        return $entity;
    }
}
