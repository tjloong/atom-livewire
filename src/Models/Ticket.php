<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Traits\HasFilters;
use Jiannius\Atom\Traits\HasUniqueNumber;

class Ticket extends Model
{
    use HasOwner;
    use HasFilters;
    use HasUniqueNumber;
    
    protected $guarded = [];

    /**
     * Get comments for ticket
     */
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Scope for search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('subject', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }
}
