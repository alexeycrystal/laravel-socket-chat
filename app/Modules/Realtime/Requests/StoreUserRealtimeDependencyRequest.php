<?php


namespace App\Modules\Realtime\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Realtime\Rules\UserExistedByListRule;
use App\Modules\User\Repositories\UserRepositoryContract;

class StoreUserRealtimeDependencyRequest extends JsonRequest
{
    public function rules(UserRepositoryContract $userRepository)
    {
        return [
            'users_ids' => [
                'required',
                'array',
                'min:1',
                new UserExistedByListRule($userRepository)
            ],
        ];
    }
}
