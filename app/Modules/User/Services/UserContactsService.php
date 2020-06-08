<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractService;
use App\Modules\User\Repositories\UserContactsRepositoryContract;

/**
 * Class UserContactsService
 * @package App\Modules\User\Services
 */
class UserContactsService extends AbstractService implements UserContactsServiceContract
{
    /**
     * @var UserContactsRepositoryContract
     */
    protected UserContactsRepositoryContract $userContactsRepository;

    /**
     * UserContactsService constructor.
     * @param UserContactsRepositoryContract $userContactsRepository
     */
    public function __construct(UserContactsRepositoryContract $userContactsRepository)
    {
        $this->userContactsRepository = $userContactsRepository;
    }

    /**
     * @param int $userId
     * @param array $contactsUsersIds
     * @return bool|null
     */
    public function addContacts(int $userId, array $contactsUsersIds): ?bool
    {
        $result = $this->userContactsRepository
            ->bulkInsertContacts($userId, $contactsUsersIds);

        if(isset($result))
            return $result;

        return null;
    }
}
