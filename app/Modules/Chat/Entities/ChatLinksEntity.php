<?php


namespace App\Modules\Chat\Entities;


use App\Generics\Entities\LinksGenericEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat links entity",
 *     description="Chat links entity",
 * )
 */
class ChatLinksEntity extends LinksGenericEntity
{
    /**
     * @OA\Property(
     *   property="meta",
     *   type="object",
     *   ref="#/components/schemas/ChatLinksMetaEntity"
     * )
     */
    public ChatLinksMetaEntity $meta;
}
