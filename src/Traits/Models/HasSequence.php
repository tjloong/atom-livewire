<?php

namespace Jiannius\Atom\Traits\Models;

trait HasSequence
{

    // boot
    protected static function bootHasSequence() : void
    {
        //
    }

    // initialize
    protected function initializeHasSequence() : void
    {
        $this->casts['seq'] = 'integer';
    }

    // scope for sequence
    public function scopeSequence($query, $order = null) : void
    {
        if ($order === 'reverse') $query->orderByDesc('seq');
        else $query->orderBy('seq');
    }
}