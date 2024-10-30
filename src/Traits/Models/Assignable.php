<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Assignable
{
    public function assigned_to() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'assigned_to_id');
    }

    public function scopeAssignedTo($query, $user) : void
    {
        $query->whereIn($this->getTable().'.assigned_to_id', collect($user)
            ->map(fn($val) => is_numeric($val) ? $val : $val->id)->toArray());
    }

    public function isAssignedTo($user) : bool
    {
        return in_array($this->assigned_to_id, collect($user)
            ->map(fn($val) => is_numeric($val) ? $val : $val->id)->toArray());
    }
}