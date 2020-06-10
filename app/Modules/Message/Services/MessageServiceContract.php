<?php


namespace App\Modules\Message\Services;


use App\Generics\Services\AbstractServiceContract;

/**
 * Interface MessageServiceContract
 * @package App\Modules\Message\Services
 */
interface MessageServiceContract extends AbstractServiceContract
{
    /**
     * @param array $payload
     * @return int|null
     */
    public function createByLoggedUser(array $payload): ?int;
}
