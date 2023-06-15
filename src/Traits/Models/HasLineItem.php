<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasLineItem
{
    /**
     * Get line items for model
     */
    public function line_items(): HasMany
    {
        return $this->hasMany(model('line_item'));
    }
}