<?php


namespace App\Generics\Repositories;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class AbstractRepository
{
    /**
     * @var Collection
     */
    private Collection $errors;

    /**
     * Check if any error occurs
     *
     * @return array|null
     */
    public function hasErrors(): ?array
    {
        if($this->errors && $this->errors->isNotEmpty())
            return $this->errors->all();

        return null;
    }

    /**
     * Append sigle error to list
     *
     * @param int $code
     * @param string $level
     * @param string $message
     * @return bool
     */
    public function addError(int $code,
                             string $level,
                             string $message): bool
    {
        if(!isset($this->errors))
            $this->errors = new Collection();

        $this->errors->push(['code'=> $code,'level'=> $level, 'message' => $message]);

        Log::debug( "=================================".PHP_EOL."
                            'code' => $code ".PHP_EOL."
                            'level'=>$level,  ".PHP_EOL."
                            'message'=>$message ".PHP_EOL.
            "=================================".PHP_EOL.""
        );

        return true;
    }

    /**
     * Append errors to list
     *
     * @param array $errors
     * @return bool
     */
    public function addErrors(array $errors): bool
    {
        if(!$this->errors)
            $this->errors = new Collection();

        $this->errors = $this->errors->concat($errors);

        return true;
    }

    public function getSqlWithBindings(Builder $builder): string
    {
        $sql = $builder->toSql();
        foreach($builder->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    public static function sqlEscapeLikeRaw(string $str)
    {
        $ret = str_replace(
            ['%', '_'],
            ['\%', '\_'],
            DB::getPdo()->quote($str)
        );

        return $ret && strlen($ret) >= 2
            ? substr($ret, 1, strlen($ret) - 2)
            : $ret;
    }
}
