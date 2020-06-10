<?php


namespace App\Modules\Message\Transformers;


use App\Generics\Transformers\AbstractTransformer;

class MessageTransformer extends AbstractTransformer
{
    public static function transformMessageStore(int $messageId): array
    {
        return [
            'data' => [
                'result' => true,
                'message_id' => $messageId,
            ]
        ];
    }

    public static function transformMessageUpdate(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
