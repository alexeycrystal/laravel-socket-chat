<?php


namespace App\Modules\Chat\Repositories;


use App\Generics\Services\AbstractServiceContract;
use App\Modules\Chat\Models\Chat;

/**
 * Interface ChatRepositoryContact
 * @package App\Modules\Chat\Repositories
 */
interface ChatRepositoryContract extends AbstractServiceContract
{
    /**
     * @param array $payload
     * @return Chat|null
     */
    public function create(array $payload): ?Chat;
}
