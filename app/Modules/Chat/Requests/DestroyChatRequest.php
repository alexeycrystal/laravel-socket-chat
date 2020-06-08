<?php


namespace App\Modules\Chat\Requests;


use App\Generics\Requests\JsonRequest;

class DestroyChatRequest extends JsonRequest
{
    public function all($keys = null)
    {
        $data = parent::all();

        $data['id'] = $this->route('chat');

        return $data;
    }

    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                'exists:chats,id',
            ]
        ];
    }
}
