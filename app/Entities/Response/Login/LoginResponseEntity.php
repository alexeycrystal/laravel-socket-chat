<?php


namespace App\Entities\Response\Login;

use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     description="Login response params",
 *     type="object",
 *     title="Login response data-entity",
 * )
 */
class LoginResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="data",
     *   type="object",
     *   ref="#/components/schemas/LoginResultEntity"
     * )
     *
     * @var LoginResultEntity
     */
    public LoginResultEntity $data;
}
