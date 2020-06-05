<?php


namespace App\Generics\Repositories\Manager;


interface RepositoryManagerContract
{
    /**
     * Runs the selected callable in transaction
     *
     * @param callable $transactional
     * @param bool|null $rollbackOnNullResult
     * @return mixed
     * @throws \Throwable
     */
    public function resolveTransactional(callable $transactional, ?bool $rollbackOnNullResult = false);
}
