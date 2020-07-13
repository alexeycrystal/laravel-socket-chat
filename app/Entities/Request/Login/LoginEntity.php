<?php


namespace App\Entities\Request\Login;

use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     description="Login request params entity",
 *     type="object",
 *     title="Login request data-entity",
 * )
 */
class LoginEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="Email",
     *     description="User email",
     *     format="email",
     *     example="example@gmail.com"
     * )
     *
     * @var string
     */
    public string $email;

    /**
     * @OA\Property(
     *     title="Password",
     *     description="User password",
     *     format="string",
     *     example="qwerty123"
     * )
     *
     * @var string
     */
    public string $password;
}
