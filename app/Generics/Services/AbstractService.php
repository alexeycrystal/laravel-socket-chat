<?php


namespace App\Generics\Services;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Class AbstractService
 * @package App\Generics\Services
 */
abstract class AbstractService implements AbstractServiceContract
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
        if(isset($this->errors) && $this->errors->isNotEmpty())
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
        if(!$this->errors)
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
}
