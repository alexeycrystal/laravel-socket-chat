<?php


namespace App\Modules\Chat\Entities\Index;


use App\Generics\Entities\AbstractEntity;
use Illuminate\Support\Collection;

/**
 * Class ChatIndexResultEntity
 * @package App\Modules\Chat\Controllers
 */
class ChatIndexResultEntity extends AbstractEntity
{
    /**
     * @var Collection
     */
    public Collection $result;

    /**
     * @var array
     */
    public array $statuses;

    /**
     * @var array
     */
    public array $users_ids;

    /**
     * @var bool
     */
    public bool $is_filterable;
}
