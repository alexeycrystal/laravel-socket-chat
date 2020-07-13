<?php


namespace App\Modules\Chat\Entities\Show;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat show service result entity",
 *     description="Chat show service result entity",
 * )
 */
class ChatShowEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="chat",
     *   type="object",
     *   ref="#/components/schemas/ChatShowResultEntity"
     * )
     */
    public ChatShowResultEntity $chat;
}
