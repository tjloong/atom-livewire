<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasShare
{
    // get share for model
    public function share(): MorphOne
    {
        return $this->morphOne(model('share'), 'parent');
    }
}