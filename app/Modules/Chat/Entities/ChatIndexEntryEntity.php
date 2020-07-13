<?php


namespace App\Modules\Chat\Entities;


use App\Generics\Entities\AbstractEntity;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Chat entry",
 *     description="Chat entry",
 * )
 */
class ChatIndexEntryEntity extends AbstractEntity
{
    /**
     * @OA\Property(
     *     title="Chat id",
     *     description="Chat id",
     *     format="int64",
     *     example="1"
     * )
     *
     * @var int
     */
    public int $chat_id;

    /**
     * @OA\Property(
     *     title="User id",
     *     description="User id",
     *     format="int64",
     *     example="1"
     * )
     *
     * @var int
     */
    public int $user_id;

    /**
     * @OA\Property(
     *     title="Chat interlocutor's name",
     *     description="Chat interlocutor's name",
     *     format="string",
     *     example="Alex"
     * )
     *
     * @var string
     */
    public string $title;

    /**
     * @OA\Property(
     *     title="Last chat message",
     *     description="Last chat message",
     *     format="string",
     *     nullable=true,
     *     example="Hello Mike! How are you?"
     * )
     **/
    public $last_message;

    /**
     * @OA\Property(
     *     title="Avatar path",
     *     description="User avatar path",
     *     format="string",
     *     example="http://127.0.0.1:8000/storage/avatars/1/avatar.jpeg"
     * )
     *
     * @var string
     */
    public string $avatar;

    /**
     * @OA\Property(
     *     title="Current user status",
     *     description="Current user status - online, busy, offline",
     *     format="string",
     *     example="http://127.0.0.1:8000/storage/avatars/1/avatar.jpeg"
     * )
     *
     * @var string
     */
    public string $status;
}
