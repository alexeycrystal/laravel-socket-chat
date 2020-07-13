<?php


namespace App\Modules\Chat\Entities\Show;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat main response entity",
 *     description="Chat main response entity",
 * )
 */
class ChatShowResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="data",
     *   type="object",
     *   ref="#/components/schemas/ChatShowEntity"
     * )
     */
    public ChatShowEntity $data;
}
