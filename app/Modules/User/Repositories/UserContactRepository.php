<?php


namespace App\Modules\User\Repositories;


use App\Generics\Repositories\AbstractRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class UserContactRepository
 * @package App\Modules\User\Repositories
 */
class UserContactRepository extends AbstractRepository implements UserContactsRepositoryContract
{
    /**
     * @param int $userId
     * @param array $params
     * @return Collection|null
     */
    public function getContactsByParams(int $userId, array $params): ?Collection
    {
        $take = $params['take'];
        $skip = $params['skip'];

        $query = DB::table('user_contacts as contact')
            ->join('users as user', function(Builder $query) use ($userId) {

                $query->on('user.id', '=', 'contact.user_id')
                    ->where('contact.user_id', '=', $userId);
            })
            ->select([
                'contact.id as contact_id',
                'user.name as contact_name',
            ])
            ->selectRaw('count("contact"."contact_user_id") over() as total_contacts')
            ->take($take)
            ->skip($skip)
            ->orderBy('contact_name');

        $result = $query->get();

        if($result && $result->isNotEmpty())
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param array $contactUsersIds
     * @return bool|null
     */
    public function bulkInsertContacts(int $userId, array $contactUsersIds): ?bool
    {
        $strValues = '';

        $lastElementIndex = count($contactUsersIds) - 1;

        foreach($contactUsersIds as $index => $contactId) {

            $strValues .= "({$userId},{$contactId})";

            if($index !== $lastElementIndex)
                $strValues .= ',';
        }

        $sql = "
            insert into user_contacts (\"user_id\",\"contact_user_id\")
            values {$strValues}
            on conflict (\"user_id\", \"contact_user_id\") do nothing
        ";

        $result = DB::statement($sql);

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $userId
     * @param int $contactId
     * @return bool|null
     */
    public function isContactExistsByUser(int $userId, int $contactId): ?bool
    {
        $result = DB::table('user_contacts')
            ->where('id', '=', $contactId)
            ->where('user_id', '=', $userId)
            ->exists();

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param $contactId
     * @return \stdClass|null
     */
    public function get($contactId): ?\stdClass
    {
        $query = DB::table('user_contacts as contact')
            ->join('users as user', function(Builder $query) use ($contactId) {

                $query->on('user.id', '=', 'contact.contact_user_id')
                    ->where('contact.id', '=', $contactId);
            })
            ->join('user_settings as settings', 'user.id', '=', 'settings.user_id')
            ->select([
                'contact.id as id',
                'contact.alias',

                'user.name',

                'settings.nickname',
                'settings.avatar_path',
            ]);

        $result = $query->first();

        if($result)
            return $result;

        return null;
    }

    /**
     * @param int $contactId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $contactId, array $payload): ?bool
    {
        $result = DB::table('user_contacts')
            ->where('id', '=', $contactId)
            ->update($payload);

        if(isset($result))
            return $result;

        return null;
    }

    /**
     * @param int $contactId
     * @return bool|null
     */
    public function delete(int $contactId): ?bool
    {
        $result = DB::table('user_contacts')
            ->where('id', '=', $contactId)
            ->delete();

        if(isset($result))
            return $result;

        return null;
    }
}
