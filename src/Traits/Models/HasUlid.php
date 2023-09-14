<?php

namespace Jiannius\Atom\Traits\Models;

trait HasUlid
{
    public $usesHasUlid = true;

    // boot
    protected static function bootHasUlid()
    {
        static::saving(function($model) {
            $model->ulid = $model->ulid ?? str()->ulid();
        });
    }

    // find ulid
    public function scopeFindUlid($query, $ulid) : mixed
    {
        return $query->where('ulid', $ulid)->first();
    }

    // find ulid or fail
    public function scopeFindUlidOrFail($query, $ulid) : mixed
    {
        return $query->where('ulid', $ulid)->firstOrFail();
    }
}