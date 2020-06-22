<?php


namespace App\Modules\Chat\Transformers;


use App\Generics\Transformers\AbstractTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Class ChatTransformer
 * @package App\Modules\Chat\Transformers
 */
class ChatTransformer extends AbstractTransformer
{
    /**
     * @param array $payload
     * @param Collection|null $data
     * @return array
     */
    public static function transformChatIndex(array $payload,
                                              ?Collection $data = null): array
    {
        $result = [];

        $totalChatCount = 0;

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';
        $defaultGroupAvatar = config('app.url') . '/storage/avatars/default/default_group_avatar.png';

        if($data) {

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

                } else {

                    $chatTitle = $firstRow->user_name;
                    $avatar = $firstRow->avatar_path
                        ? config('app.url') . $firstRow->avatar_path
                        : $defaultAvatarUrl;
                }

                $entry = [
                    'chat_id' => $firstRow->chat_id,
                    'title' => $chatTitle,
                    'avatar' => $avatar,
                ];

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
