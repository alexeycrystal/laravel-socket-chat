<?php


namespace App\Modules\Message\Transformers;


use App\Generics\Transformers\AbstractTransformer;
use App\Modules\Message\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Class MessageTransformer
 * @package App\Modules\Message\Transformers
 */
class MessageTransformer extends AbstractTransformer
{
    public static function transformMessagesIndex(array $payload,
                                                  ?array $messages = null)
    {
        $result = [];

        $totalMessages = 0;

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        $page = $payload['page'] ?? 1;

        if($messages) {

            $payload = $messages['payload'];

            $messages = $messages['result'];

            $firstEntry = $messages->first();

            $totalMessages = $firstEntry->total_messages;

            foreach($messages as $message) {

                $result[] = [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'chat_id' => $message->chat_id,
                    'text' => $message->text,
                    'avatar' => $message->avatar_path
                        ? config('app.url') . $message->avatar_path
                        : $defaultAvatarUrl,
                    'created_at' => $message->created_at,
                ];
            }
        }

        $currentRouteName = Route::currentRouteName();

        $meta = [
            'total_messages' => $totalMessages,
        ];

        $page = $payload && isset($payload['page'])
            ? $payload['page']
            : null;

        $links = self::preparePaginatedMeta(
            $currentRouteName,
            $page,
            $totalMessages,
            $payload['per_page'],
        );

        $links['meta'] = $meta;

        return [
            'data' => [
                'messages' => $result,
            ],
            'links' => $links,
        ];
    }

    /**
     * @param Message $message
     * @return array|array[]
     */
    public static function transformMessageStore(Message $message): array
    {
        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        return [
            'data' => [
                'result' => true,
                'message' => [
                    'id' => $message->id,
                    'chat_id' => $message->chat_id,
                    'user_id' => $message->user_id,
                    'text' => $message->text,
                    'created_at' => $message->created_at,
                    'avatar' => $message->avatar
                        ? config('app.url') . $message->avatar
                        : $defaultAvatarUrl,
                ],
            ]
        ];
    }

    /**
     * @param bool $result
     * @return array|\bool[][]
     */
    public static function transformMessageUpdate(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }

    /**
     * @param bool $result
     * @return array|\bool[][]
     */
    public static function transformMessageDestroy(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
