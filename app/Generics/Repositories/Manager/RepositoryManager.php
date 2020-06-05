<?php


namespace App\Generics\Repositories;


use App\Generics\Repositories\Manager\RepositoryManagerContract;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepositoryManager implements RepositoryManagerContract
{
    /**
     * Runs the selected callable in transaction
     *
     * @param callable $transactional
     * @param bool|null $rollbackOnNullResult
     * @return mixed
     * @throws \Throwable
     */
    public function resolveTransactional(callable $transactional, ?bool $rollbackOnNullResult = false)
    {
        $result = null;

        DB::beginTransaction();

        try {

            $result = call_user_func($transactional);

            if($rollbackOnNullResult && !isset($result)) {

                DB::rollback();

                return null;
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollback();

            Log::debug('Exception: ('
                . $e->getLine() . ') - Code:'
                . $e->getCode() . ' - '
                . $e->getMessage() . "\n "
                . $e->getTraceAsString());
            return null;
        }

        return $result;
    }
}
