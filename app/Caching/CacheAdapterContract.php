<?php

namespace App\Caching;

/**
 * Interface CacheAdapterContract
 * @package App\Caching
 */
interface CacheAdapterContract
{
    /**
     * @param string $ending
     * @return mixed
     */
    public function setEnding(string $ending);
    
    /**
     * @param string $cachePath
     * @return mixed
     */
    public function setCachePath(string $cachePath);
    
    /**
     * @param string $fullPath
     */
    public function getData(string $fullPath);
    
    /**
     * @param string $ending
     */
    public function getDataByEndingSelector(?string $ending = null);
    
    /**
     * @param string $append
     * @return mixed
     */
    public function appendToPath(string $append);
    
    /**
     * @param string|null $fullPath
     * @param array $data
     * @param int|null $seconds
     * @return void
     */
    public function storeData(string $fullPath, $data, ?int $seconds = 300): void;
    
    /**
     * @param array $data
     * @param int|null $seconds
     * @param string|null $endingSelector
     * @return bool|null
     */
    public function storeDataByEndingSelector($data, ?int $seconds = 300, ?string $endingSelector = null): ?bool;

}
