<?php


namespace App\Modules\Auth\Transformers;


use App\Entities\Response\Registration\RegistrationResponseEntity;
use App\Entities\Response\Registration\RegistrationResultEntity;
use App\Generics\Transformers\AbstractTransformer;

class RegistrationTransformer extends AbstractTransformer
{
    public static function transform(RegistrationResultEntity $data): RegistrationResponseEntity
    {
        $entity = new RegistrationResponseEntity();

        $entity->data = $data;

        return $entity;
    }
}
