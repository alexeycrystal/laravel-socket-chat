<?php


namespace App\Modules\Chat\Repositories;


use App\Generics\Services\AbstractServiceContract;

/**
 * Interface ChatUserRepositoryContract
 * @package App\Modules\Chat\Repositories
 */
interface ChatUserRepositoryContract
{
    /**
     * @param array $payload
     * @return bool|null
     */
    public function bulkInsert(array $payload): ?bool;
}
