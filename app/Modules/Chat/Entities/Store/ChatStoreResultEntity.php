<?php


namespace App\Modules\Chat\Entities\Store;


use App\Generics\Entities\AbstractEntity;

class ChatStoreResultEntity extends AbstractEntity
{
    public int $chat_id;

    public \stdClass $user_meta_info;

    public bool $chat_already_exists;
}
