<?php


namespace App\Modules\Message\Repositories;


use App\Modules\Message\Models\Message;

/**
 * Interface MessageRepositoryContract
 * @package App\Modules\Message\Repositories
 */
interface MessageRepositoryContract
{
    /**
     * @param array $payload
     * @return Message|null
     */
    public function create(array $payload): ?Message;
}
