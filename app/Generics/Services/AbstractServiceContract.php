<?php


namespace App\Generics\Services;


interface AbstractServiceContract
{
    public function hasErrors(): ?array;

    public function addError(int $code,
                             string $level,
                             string $message): bool;

    public function addErrors(array $errors): bool;
}
