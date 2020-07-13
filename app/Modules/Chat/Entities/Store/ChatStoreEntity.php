<?php


namespace App\Modules\Chat\Entities\Store;


use App\Generics\Entities\AbstractEntity;
use App\Modules\Chat\Entities\Show\ChatShowResultEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat inner entity",
 *     description="Chat inner entity",
 * )
 */
class ChatStoreEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="Chat id",
     *     description="Chat id",
     *     format="int64",
     *     example=1
     * )
     */
    public int $chat_id;

    /**
     * @OA\Property(
     *   property="chat",
     *   type="object",
     *   ref="#/components/schemas/ChatShowResultEntity"
     * )
     */
    public ChatShowResultEntity $chat;
}
