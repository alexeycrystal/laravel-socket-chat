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

        if($data) {

            $first = $data->first();

            $totalContactsCount = $first->total_contacts;

            foreach($data as $contact) {

                $result[] = [
                    'user_id' => $contact->contact_id,
                    'name' => $contact->contact_name,
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
}
