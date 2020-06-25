<?php

namespace App\Caching;

use Illuminate\Support\Facades\Cache;

/**
 * Class AbstractCacheAdapter
 * @package App\Caching
 */
class AbstractCacheAdapter implements CacheAdapterContract
{
    protected $alreadyInited = false;

    /**
     * @var string|null
     */
    protected $cachePath;

    /**
     * @var string|null
     */
    protected $pathEnding;

    /**
     * AbstractCacheAdapter constructor.
     * @param string|null $cachePath
     * @param string|null $pathEnding
     */
    public function __construct(?string $cachePath = null, ?string $pathEnding = null)
    {
        $this->cachePath = $cachePath;

        $this->pathEnding = $pathEnding;
    }

    /**
     * @param string $ending
     * @return $this|mixed
     */
    public function setEnding(string $ending)
    {
        $this->pathEnding = $ending;

        return $this;
    }

    /**
     * @param mixed $cachePath
     * @return AbstractCacheAdapter
     */
    public function setCachePath(string $cachePath)
    {
        $this->cachePath = $cachePath;

        return $this;
    }

    /**
     * @param string $fullPath
     * @return mixed
     */
    public function getData(string $fullPath)
    {
        $data = Cache::get($fullPath);

        if($data)
            return json_decode($data, true);

        return null;
    }

    /**
     * @
     * param string $ending
     * @param string $ending
     * @return mixed
     */
    public function getDataByEndingSelector(?string $ending = null)
    {
        $ending = $ending ?? $this->pathEnding;

        if(!$this->cachePath
            || !$ending)
            return false;

        $data = Cache::get($this->cachePath . ':' . $ending);

        if($data)
            return json_decode($data, true);

        return null;
    }

    /**
     * @param string $append
     * @return mixed
     */
    public function appendToPath(string $append)
    {
        $this->cachePath = $this->cachePath . ':' . $append;

        return $this;
    }

    /**
     * @param string|null $fullPath
     * @param array $data
     * @param int|null $seconds
     * @return void
     */
    public function storeData(string $fullPath, $data, ?int $seconds = 300): void
    {
        Cache::put($fullPath, json_encode($data), now()->addSeconds($seconds));
    }

    /**
     * @param array $data
     * @param int|null $seconds
     * @param string|null $ending
     * @return bool|null
     */
    public function storeDataByEndingSelector($data, ?int $seconds = 300, ?string $ending = null): ?bool
    {
        $ending = $ending ?? $this->pathEnding;

        if(!$this->cachePath
            || !$ending)
            return false;

        Cache::put($this->cachePath . ':' . $ending, json_encode($data), now()->addSeconds($seconds));

        return true;
    }
}
