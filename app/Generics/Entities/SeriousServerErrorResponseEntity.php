<?php


namespace App\Generics\Entities;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Serious unspecified server error",
 *     description="Happend only in the serious database issues / packages / store problems.",
 * )
 */
class SeriousServerErrorResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="Error message",
     *     description="Error message",
     *     type="string",
     *     example="Some serious server error occurs."
     * )
     *
     * @var string
     */
    public string $error;
}
