<?php


namespace App\Modules\Realtime\Rules;


use App\Modules\User\Repositories\UserRepositoryContract;
use Illuminate\Contracts\Validation\Rule;

class UserExistedByListRule implements Rule
{
    protected UserRepositoryContract $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function passes($attribute, $userIds)
    {
        $result = $this->userRepository
            ->isUsersListExisted($userIds);

        if($result)
            return true;

        return false;
    }

    public function message()
    {
        return 'Some of the user ids is not valid or user not exists.';
    }
}
