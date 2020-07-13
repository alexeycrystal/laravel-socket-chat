<?php


namespace App\Modules\Chat\Entities\Delete;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat delete result entity",
 *     description="Chat delete result entity",
 * )
 */
class ChatDeleteResponseEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *   property="data",
     *   type="object",
     *   @OA\Property(property="result", type="boolean")
     * )
     */
    public $data;
}
