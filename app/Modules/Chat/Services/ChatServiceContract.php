<?php


namespace App\Modules\Chat\Services;


use App\Generics\Services\AbstractService;
use App\Generics\Services\AbstractServiceContract;

/**
 * Interface ChatServiceContract
 * @package App\Modules\Chat\Services
 */
interface ChatServiceContract extends AbstractServiceContract
{
    /**
     * @param array $payload
     * @return array|null
     */
    public function createChat(array $payload): ?array;
}
