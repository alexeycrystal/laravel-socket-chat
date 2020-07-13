<?php


namespace App\Modules\Chat\Entities\Index;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat data links",
 *     description="Chat data links",
 * )
 */
class ChatLinksMetaEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="Total chats count",
     *     description="Total chats count",
     *     format="int64",
     *     example="1"
     * )
     *
     * @var int
     */
    public int $total_chats;
}
