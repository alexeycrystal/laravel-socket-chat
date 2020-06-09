<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractServiceContract;
use Illuminate\Support\Collection;

/**
 * Interface UserContactsServiceContract
 * @package App\Modules\User\Services
 */
interface UserContactsServiceContract extends AbstractServiceContract
{
    /**
     * @param array $params
     * @return Collection|null
     */
    public function getContactsByParams(array $params): ?Collection;

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
