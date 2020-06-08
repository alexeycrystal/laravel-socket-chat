<?php


namespace App\Modules\User\Services;


/**
 * Interface UserContactsServiceContract
 * @package App\Modules\User\Services
 */
interface UserContactsServiceContract
{
    /**
     * @param int $contactUserId
     * @return bool|null
     */
    public function addContactToLoggedUser(int $contactUserId): ?bool;

    /**
     * @param int $userId
     * @param array $contactsUsersIds
     * @return bool|null
     */
    public function addContacts(int $userId, array $contactsUsersIds): ?bool;
}
