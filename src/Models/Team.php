<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasTrace;
    use HasFilters;
    
    protected $guarded = [];

    /**
     * Get users for team
     */
    public function users()
    {
        return $this->belongsToMany(model('user'), 'team_users');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhereHas('users', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for assignable
     */
    public function assignable($query)
    {
        return $query;
    }
}
