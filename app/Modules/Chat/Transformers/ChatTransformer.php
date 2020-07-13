<?php


namespace App\Modules\Chat\Transformers;


use App\Generics\Transformers\AbstractTransformer;
use App\Modules\Chat\Entities\Delete\ChatDeleteResponseEntity;
use App\Modules\Chat\Entities\Index\ChatIndexEntryEntity;
use App\Modules\Chat\Entities\Index\ChatIndexResponseEntity;
use App\Modules\Chat\Entities\Index\ChatIndexResultEntity;
use App\Modules\Chat\Entities\Index\ChatIndexEntity;
use App\Modules\Chat\Entities\Index\ChatLinksEntity;
use App\Modules\Chat\Entities\Index\ChatLinksMetaEntity;
use App\Modules\Chat\Entities\Show\ChatShowEntity;
use App\Modules\Chat\Entities\Show\ChatShowResponseEntity;
use App\Modules\Chat\Entities\Show\ChatShowResultEntity;
use App\Modules\Chat\Entities\Store\ChatStoreEntity;
use App\Modules\Chat\Entities\Store\ChatStoreResponseEntity;
use App\Modules\Chat\Entities\Store\ChatStoreResultEntity;
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

        $links = new ChatLinksEntity(self::preparePaginatedMeta(
            $currentRouteName,
            $payload->page,
            $totalChatCount,
            $payload->per_page,
        ));

        $links->meta = $meta;

        return new ChatIndexResponseEntity([
            'data' => $result,
            'links' => $links
        ]);
    }

    /**
     * @param ChatStoreResultEntity $data
     * @return ChatStoreResponseEntity
     */
    public static function transformChatCreated(ChatStoreResultEntity $data): ChatStoreResponseEntity
    {
        $chat = $data->user_meta_info;

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        $status = $data->status ?? '';

        $entry = new ChatShowResultEntity([
            'chat_id' => $data->chat_id,
            'user_id' => $chat->user_id,
            'title' => $chat->name,
            'last_message' => '',
            'avatar' => isset($chat->avatar)
                ? config('app.url') . $chat->avatar
                : $defaultAvatarUrl,
            'status' => $status,
        ]);

        $storeEntity = new ChatStoreEntity([
            'chat_id' => $data->chat_id,
            'chat' => $entry,
        ]);

        return new ChatStoreResponseEntity([
            'data' => $storeEntity,
        ]);
    }

    /**
     * @param bool $result
     * @return ChatDeleteResponseEntity
     */
    public static function chatDeleteSuccess(bool $result): ChatDeleteResponseEntity
    {
        return new ChatDeleteResponseEntity([
            'data' => [
                'result' => $result,
            ],
        ]);
    }

    public static function transformShowChat(ChatShowResultEntity $data): ChatShowResponseEntity
    {
        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        $data->status = $data->status && $data->status !== 'offline'
            ? $data->status
            : '';

        $data->avatar = isset($data->avatar)
            ? config('app.url') . $data->avatar
            : $defaultAvatarUrl;

        $chatShow = new ChatShowEntity();
        $chatShow->chat = $data;

        $result = new ChatShowResponseEntity();
        $result->data = $chatShow;

        return $result;
    }
}
