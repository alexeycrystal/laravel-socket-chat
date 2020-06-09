<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Repositories\UserContactsRepositoryContract;
use Illuminate\Support\Collection;

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
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;

    /**
     * UserContactsService constructor.
     * @param AuthServiceContract $authService
     * @param UserContactsRepositoryContract $userContactsRepository
     */
    public function __construct(AuthServiceContract $authService,
                                UserContactsRepositoryContract $userContactsRepository)
    {
        $this->authService = $authService;
        $this->userContactsRepository = $userContactsRepository;
    }

    /**
     * @param array $params
     * @return Collection|null
     */
    public function getContactsByParams(array $params): ?Collection
    {
        $user = $this->authService->getLoggedUser();

        $payload = [
            'take' => $params['per_page'],
            'skip' => $params['page'] > 1
                ? $params['per_page'] * $params['page']
                : 0,
        ];

        $result = $this->userContactsRepository
            ->getContactsByParams($user->id, $payload);

        if($result)
            return $result;

        return null;
    }

    /**
     * @param int $contactUserId
     * @return bool|null
     */
    public function addContactToLoggedUser(int $contactUserId): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->userContactsRepository
            ->bulkInsertContacts($user->id, [$contactUserId]);

        if($result)
            return $result;

        return null;
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
