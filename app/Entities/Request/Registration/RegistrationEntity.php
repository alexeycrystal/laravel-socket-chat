<?php


namespace App\Entities\Request\Registration;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     description="Registration request params entity",
 *     type="object",
 *     title="Registration request data-entity",
 * )
 */
class RegistrationEntity extends AbstractEntity
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
     *     example="123qwerty"
     * )
     *
     * @var string
     */
    public string $password;

    /**
     * @OA\Property(
     *     title="Password confirmation field",
     *     description="Must be equal to password",
     *     format="string",
     *     example="123qwerty"
     * )
     *
     * @var string
     */
    public string $password_confirmation;

    /**
     * @OA\Property(
     *     title="Username",
     *     description="Username property",
     *     format="string",
     *     example="Alex"
     * )
     *
     * @var string
     */
    public string $name;

    /**
     * @OA\Property(
     *     title="Selected language",
     *     description="Language property. Available values: en,ru.",
     *     format="string",
     *     example="en"
     * )
     *
     * @var string
     */
    public string $lang;
}
