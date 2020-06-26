<?php


namespace App\Modules\User\Requests\Status;


use App\Generics\Requests\JsonRequest;

class UpdateUserStatusRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'status' => [
                'required',
                'string',
                'in:online,offline,away,busy'
            ],
        ];
    }
}
