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
class ChatShowResultEntity extends AbstractEntity
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
     *     title="User id",
     *     description="User id",
     *     format="int64",
     *     example=1
     * )
     */
    public int $user_id;

    /**
     * @OA\Property(
     *     title="Chat title",
     *     description="Chat user name (ussually name of the user is the chat name)",
     *     format="string",
     *     example="Alex"
     * )
     */
    public string $title;

    /**
     * @OA\Property(
     *     title="Last chat message",
     *     description="Last chat message",
     *     format="string",
     *     nullable=true,
     *     example="Alex"
     * )
     */
    public $last_message;

    /**
     * @OA\Property(
     *     title="User avatar",
     *     description="User avatar",
     *     format="string",
     *     example="http://127.0.0.1:8000/storage/avatars/default/default_avatar.png"
     * )
     */
    public string $avatar;

    /**
     * @OA\Property(
     *     title="User current status",
     *     description="online, offline, busy statuses",
     *     format="string",
     *     example="online"
     * )
     */
    public string $status;
}
