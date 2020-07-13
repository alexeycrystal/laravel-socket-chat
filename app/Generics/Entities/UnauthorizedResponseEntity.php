<?php


namespace App\Generics\Entities;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Unauthorized response entity",
 *     description="Unauthorized response entity",
 * )
 */
class UnauthorizedResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="Error message",
     *     description="Error message",
     *     type="string",
     *     example="You are not authorized for this action."
     * )
     *
     * @var string
     */
    public string $message;

    /**
     * @OA\Property(
     *     title="HTTP error code",
     *     description="HTTP error code",
     *     type="int",
     *     example=401
     * )
     *
     * @var string
     */
    public int $status_code;
}
