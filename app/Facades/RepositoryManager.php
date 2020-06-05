<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed resolveTransactional(callable $callback, ?bool $rollbackOnNullResult = false)
 **/
class RepositoryManager extends Facade
{
	protected static function getFacadeAccessor() { return 'RepositoryManager'; }
}
