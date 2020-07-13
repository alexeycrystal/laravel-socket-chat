<?php


namespace App\Modules\Chat\Transformers;


use App\Generics\Transformers\AbstractTransformer;
use App\Modules\Chat\Entities\ChatIndexEntryEntity;
use App\Modules\Chat\Entities\ChatIndexResponseEntity;
use App\Modules\Chat\Entities\ChatIndexResultEntity;
use App\Modules\Chat\Entities\ChatIndexEntity;
use App\Modules\Chat\Entities\ChatLinksMetaEntity;
use Illuminate\Support\Facades\Route;

/**
 * Class ChatTransformer
 * @package App\Modules\Chat\Transformers
 */
class ChatTransformer extends AbstractTransformer
{
    /**
     * @param ChatIndexEntity $payload
     * @param ChatIndexResultEntity|null $data
     * @return ChatIndexResponseEntity
     * @throws \Exception
     */
    public static function transformChatIndex(ChatIndexEntity $payload,
                                              ?ChatIndexResultEntity $data = null): ChatIndexResponseEntity
    {
        $result = [];

        $totalChatCount = 0;

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';
        $defaultGroupAvatar = config('app.url') . '/storage/avatars/default/default_group_avatar.png';

        if($data) {

            $statuses = $data->statuses;
            $usersIds = $data->users_ids;

            $statusResult = [];
            foreach($usersIds as $index => $userId)
                $statusResult[$userId] = $statuses[$index] ?? '';

            $data = $data->result;

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

                $entry = new ChatIndexEntryEntity([
                    'chat_id' => $firstRow->chat_id,
                    'user_id' => $userId,
                    'title' => $chatTitle,
                    'last_message' => $firstRow->last_message_thumb,
                    'avatar' => $avatar,
                    'status' => $status,
                ]);

                if(isset($firstRow->message_id))
                    $entry['message_id'] = $firstRow->message_id;

                $result[] = $entry;
            }
        }

        $currentRouteName = Route::currentRouteName();

        $meta = new ChatLinksMetaEntity([
            'total_chats' => $totalChatCount,
        ]);

        $links = self::preparePaginatedMeta(
            $currentRouteName,
            $payload->page,
            $totalChatCount,
            $payload->per_page,
        );

        $links->meta = $meta;

        return new ChatIndexResponseEntity([
            'data' => $result,
            'links' => $links
        ]);
    }

    /**
     * @param array $data
     * @return array|array[]
     */
    public static function transformChatCreated(array $data): array
    {
        $chat = $data['user_meta_info'];

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        $status = $data['status'][0] ?? '';

        $entry = [
            'chat_id' => $data['chat_id'],
            'user_id' => $chat->user_id,
            'title' => $chat->name,
            'last_message' => '',
            'avatar' => isset($chat->avatar)
                ? config('app.url') . $chat->avatar
                : $defaultAvatarUrl,
            'status' => $status,
        ];

        return [
            'data' => [
                'chat_id' => $data['chat_id'],
                'chat' => $entry
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

    public static function transformShowChat(\stdClass $data): array
    {
        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        $status = $data->status && $data->status !== 'offline'
            ? $data->status
            : '';

        $entry = [
            'chat_id' => $data->chat_id,
            'user_id' => $data->user_id,
            'title' => $data->name,
            'last_message' => $data->last_message_thumb,
            'avatar' => isset($chat->avatar)
                ? config('app.url') . $chat->avatar
                : $defaultAvatarUrl,
            'status' => $status,
        ];

        return [
            'data' => [
                'chat' => $entry
            ]
        ];
    }
}
