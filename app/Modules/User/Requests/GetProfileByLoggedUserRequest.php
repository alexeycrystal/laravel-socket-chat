<?php


namespace App\Modules\User\Requests;


use App\Generics\Requests\JsonRequest;

class GetProfileByLoggedUserRequest extends JsonRequest
{
    public function rules()
    {
        return [];
    }
}
