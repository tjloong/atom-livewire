<?php

namespace Jiannius\Atom\Traits\Models;

trait Cache
{
    public function cache($name = null, $default = null) : mixed
    {
        if ($name === false) return $this->clearCache();
        elseif ($name) return data_get($this->cache(), $name, $default);
        else {
            return cache()->remember(
                $this->getCacheKey(),
                now()->addDays($this->getCacheDuration()),
                fn() => $this->cacheable(),
            );
        }
    }

    // cacheable
    public function cacheable() : array
    {
        return [];
    }

    // clear cache
    public function clearCache() : void
    {
        cache()->forget($this->getCacheKey());
    }

    // get cache key
    public function getCacheKey() : string
    {
        return str($this->getTable())->singular()->toString().'_'.$this->id;
    }

    // get cache duration in days
    public function getCacheDuration() : int
    {
        return 7;
    }
}