<?php


namespace App\Modules\Chat\Entities;


use App\Entities\Response\Registration\RegistrationResultEntity;
use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Get chat list",
 *     description="Get chat list by params",
 * )
 */
class ChatIndexResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="data",
     *   type="array",
     *   @OA\Items(ref="#/components/schemas/ChatIndexEntryEntity")
     * )
     *
     * @var array
     */
    public array $data;

    /**
     * @OA\Property(
     *   property="links",
     *   type="object",
     *   ref="#/components/schemas/ChatLinksEntity"
     * )
     */
    public ChatLinksEntity $links;
}
