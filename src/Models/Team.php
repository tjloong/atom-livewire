<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFilters;
    use HasTrace;
    
    protected $guarded = [];

    /**
     * Get users for team
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(model('user'), 'team_users');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhereHas('users', fn($q) => $q->search($search))
        );
    }
}
