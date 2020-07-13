<?php


namespace App\Entities\Response\Registration;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     description="Registration response params",
 *     type="object",
 *     title="Registration response data-entity",
 * )
 */
class RegistrationResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="data",
     *   type="object",
     *   ref="#/components/schemas/RegistrationResultEntity"
     * )
     *
     * @var RegistrationResultEntity
     */
    public RegistrationResultEntity $data;
}
