<?php


namespace App\Modules\Chat\Transformers;


use App\Generics\Transformers\AbstractTransformer;
use Illuminate\Support\Facades\Route;

/**
 * Class ChatTransformer
 * @package App\Modules\Chat\Transformers
 */
class ChatTransformer extends AbstractTransformer
{
    /**
     * @param array $payload
     * @param array|null $data
     * @return array
     */
    public static function transformChatIndex(array $payload,
                                              ?array $data = null): array
    {
        $result = [];

        $totalChatCount = 0;

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';
        $defaultGroupAvatar = config('app.url') . '/storage/avatars/default/default_group_avatar.png';

        if($data) {

            $statuses = $data['statuses'];
            $usersIds = $data['users_ids'];

            $statusResult = [];
            foreach($usersIds as $index => $userId)
                $statusResult[$userId] = $statuses[$index] ?? '';

            $data = $data['result'];

            $totalChatCount = $data->first()->total_chats;

            $data = $data->groupBy('chat_id');

            foreach($data as $chatId => $users) {

                $firstRow = $users->first();

                $usersCount = $users->count();

                if($usersCount > 1) {

                    $titles = $users->pluck('user_name')
                        ->values()
                        ->sort()
                        ->implode(',');

                    $chatTitle = "({$usersCount}) " . $titles;

                    $avatar = $defaultGroupAvatar;

                    $status = '';

                    $userId = null;

                } else {

                    $chatTitle = $firstRow->user_name;
                    $avatar = $firstRow->avatar_path
                        ? config('app.url') . $firstRow->avatar_path
                        : $defaultAvatarUrl;

                    $status = $statusResult[$firstRow->user_id];

                    $userId = $firstRow->user_id;
                }

                $entry = [
                    'chat_id' => $firstRow->chat_id,
                    'user_id' => $userId,
                    'title' => $chatTitle,
                    'last_message' => $firstRow->last_message_thumb,
                    'avatar' => $avatar,
                    'status' => $status,
                ];

                if(isset($firstRow->message_id))
                    $entry['message_id'] = $firstRow->message_id;

                $result[] = $entry;
            }
        }

        $currentRouteName = Route::currentRouteName();

        $meta = [
            'total_chats' => $totalChatCount,
        ];

        $links = self::preparePaginatedMeta(
            $currentRouteName,
            $payload['page'],
            $totalChatCount,
            $payload['per_page'],
        );

        $links['meta'] = $meta;

        return [
            'data' => $result,
            'links' => $links
        ];
    }

    /**
     * @param array $data
     * @return array|array[]
     */
    public static function transformChatCreated(array $data): array
    {
        return [
            'data' => [
                'chat_id' => $data['chat_id']
            ]
        ];
    }

    /**
     * @param bool $result
     * @return array|\bool[][]
     */
    public static function chatDeleteSuccess(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
