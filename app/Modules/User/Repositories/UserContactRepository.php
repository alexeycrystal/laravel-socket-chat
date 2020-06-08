<?php


namespace App\Modules\User\Repositories;


use App\Generics\Repositories\AbstractRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserContactRepository
 * @package App\Modules\User\Repositories
 */
class UserContactRepository extends AbstractRepository implements UserContactsRepositoryContract
{
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
