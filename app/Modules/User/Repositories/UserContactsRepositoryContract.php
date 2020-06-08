<?php


namespace App\Modules\User\Repositories;


/**
 * Interface UserContactsRepositoryContract
 * @package App\Modules\User\Repositories
 */
interface UserContactsRepositoryContract
{
    /**
     * @param int $userId
     * @param array $contactUsersIds
     * @return bool|null
     */
    public function bulkInsertContacts(int $userId, array $contactUsersIds): ?bool;
}
