<?php

namespace Jiannius\Atom\Traits\Models;

trait Archiveable
{
    // boot
    protected static function bootArchiveable() : void
    {
        static::addGlobalScope('archived', function($builder) {
            $builder->whereNull('archived_at');
        });
    }

    // initialize
    protected function initializeArchiveable() : void
    {
        $this->casts['archived_at'] = 'datetime';
    }

    // scope for with archived
    public function scopeWithArchived($query) : void
    {
        $query->withoutGlobalScope('archived');
    }

    // scope for only archived
    public function scopeOnlyArchived($query) : void
    {
        $query->withArchived()->whereNotNull('archived_at');
    }

    // check model is archived
    public function isArchived() : bool
    {
        return !empty($this->archived_at);
    }

    // mark as archived
    public function markArchived($bool = true) : void
    {
        $this->fill([
            'archived_at' => $bool === false ? null : now(),
            'archived_by' => $bool === false ? null : user('id'),
        ])->save();
    }
}