<?php


namespace App\Modules\User\Transformers\UserContact;


use App\Generics\Transformers\AbstractTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Class UserContactTransformer
 * @package App\Modules\User\Transformers\UserContact
 */
class UserContactTransformer extends AbstractTransformer
{
    /**
     * @param array $params
     * @param Collection $data
     * @return array
     */
    public static function transformContactIndex(array $params,
                                                 ?Collection $data = null): array
    {
        $result = [];

        $totalContactsCount = 0;

        $defaultAvatarUrl = config('app.url') . '/storage/avatars/default/default_avatar.png';

        if($data) {

            $first = $data->first();

            $totalContactsCount = $first->total_contacts;

            foreach($data as $contact) {

                $result[] = [
                    'user_id' => $contact->contact_id ?? $contact->user_id,
                    'name' => $contact->contact_name,
                    'avatar' => $first->avatar
                        ? config('app.url') . $first->avatar
                        : $defaultAvatarUrl
                ];
            }
        }

        $currentRouteName = Route::currentRouteName();

        $links = self::preparePaginatedMeta(
            $currentRouteName,
            $params['page'],
            $totalContactsCount,
            $params['per_page'],
        );

        return [
            'data' => [
                'contacts' => $result,
            ],
            'links' => $links,
        ];
    }

    /**
     * @param bool $result
     * @return array|\bool[][]
     */
    public static function transformContactStore(bool $result): array
    {
        return [
            'data' => [
                'result' => $result
            ]
        ];
    }

    public static function transformContactShow(\stdClass $contact)
    {
        return [
            'data' => [
                'id' => $contact->id,
                'name' => $contact->alias ?? $contact->name,
                'nickname' => $contact->nickname,
                'avatar' => $contact->avatar_path,
            ]
        ];
    }

    public static function transformContactUpdate(bool $result): array
    {
        return [
            'data' => [
                'result' => $result
            ]
        ];
    }

    public static function transformContactDestroy(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
