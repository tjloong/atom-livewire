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
        static::creating(function($model) {
            $model->ulid = str()->ulid();
        });
    }
}