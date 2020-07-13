<?php


namespace App\Modules\Chat\Entities;


use App\Generics\Entities\AbstractEntity;

class ChatIndexEntity extends AbstractEntity
{
    public int $per_page;

    public int $page;

    public string $filter;
}
