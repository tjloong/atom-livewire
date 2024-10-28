<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Shareable
{
    public function shareable(): MorphOne
    {
        return $this->morphOne(model('shareable'), 'parent');
    }
}