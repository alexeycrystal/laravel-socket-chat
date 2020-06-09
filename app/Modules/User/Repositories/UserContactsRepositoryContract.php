<?php


namespace App\Modules\User\Repositories;


use Illuminate\Support\Collection;

/**
 * Interface UserContactsRepositoryContract
 * @package App\Modules\User\Repositories
 */
interface UserContactsRepositoryContract
{
    /**
     * @param int $userId
     * @param array $params
     * @return Collection|null
     */
    public function getContactsByParams(int $userId, array $params): ?Collection;

    /**
     * @param int $userId
     * @param array $contactUsersIds
     * @return bool|null
     */
    public function bulkInsertContacts(int $userId, array $contactUsersIds): ?bool;

    /**
     * @param int $userId
     * @param int $contactId
     * @return bool|null
     */
    public function isContactExistsByUser(int $userId, int $contactId): ?bool;

    /**
     * @param $contactId
     * @return \stdClass|null
     */
    public function get($contactId): ?\stdClass;

    /**
     * @param int $contactId
     * @param array $payload
     * @return bool|null
     */
    public function update(int $contactId, array $payload): ?bool;
}
