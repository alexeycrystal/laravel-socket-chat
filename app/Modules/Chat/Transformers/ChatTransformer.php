<?php


namespace App\Modules\Chat\Transformers;


class ChatTransformer
{
    public static function transformChatCreated(array $data): array
    {
        return [
            'data' => [
                'chat_id' => $data['chat_id']
            ]
        ];
    }
}
