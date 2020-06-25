<?php


namespace App\Modules\Realtime\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;

class DestroyUserDependencyRequest extends JsonRequest
{
    public function rules(AuthServiceContract $authService)
    {
        return [

        ];
    }
}
