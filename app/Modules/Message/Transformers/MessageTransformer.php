<?php


namespace App\Modules\Message\Transformers;


use App\Generics\Transformers\AbstractTransformer;

/**
 * Class MessageTransformer
 * @package App\Modules\Message\Transformers
 */
class MessageTransformer extends AbstractTransformer
{
    /**
     * @param int $messageId
     * @return array|array[]
     */
    public static function transformMessageStore(int $messageId): array
    {
        return [
            'data' => [
                'result' => true,
                'message_id' => $messageId,
            ]
        ];
    }

    /**
     * @param bool $result
     * @return array|\bool[][]
     */
    public static function transformMessageUpdate(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }

    /**
     * @param bool $result
     * @return array|\bool[][]
     */
    public static function transformMessageDestroy(bool $result): array
    {
        return [
            'data' => [
                'result' => $result,
            ]
        ];
    }
}
