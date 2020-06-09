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
                'user.id as contact_id',
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
}
