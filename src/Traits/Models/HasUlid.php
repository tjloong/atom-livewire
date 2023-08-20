<?php

namespace Jiannius\Atom\Traits\Models;

trait HasUlid
{
    public $usesHasUlid = true;

    /**
     * Model boot
     */
    protected static function bootHasUlid()
    {
        static::saving(function($model) {
            $model->ulid = $model->ulid ?? str()->ulid();
        });
    }
}