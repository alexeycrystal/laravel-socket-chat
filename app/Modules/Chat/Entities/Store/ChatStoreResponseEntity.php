<?php


namespace App\Modules\Chat\Entities\Store;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat store main response entity",
 *     description="Chat store main response entity",
 * )
 */
class ChatStoreResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="data",
     *   type="object",
     *   ref="#/components/schemas/ChatStoreEntity"
     * )
     */
    public ChatStoreEntity $data;
}
