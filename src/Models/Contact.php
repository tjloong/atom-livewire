<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;

class Contact extends Model
{
    use HasFactory;
    use HasFilters;
    use HasTrace;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'avatar_id' => 'integer',
    ];

    /**
     * Get avatar for contact
     */
    public function avatar()
    {
        return $this->belongsTo(get_class(model('file')));
    }

    /**
     * Get persons for contact
     */
    public function persons()
    {
        return $this->hasMany(get_class(model('contact_person')));
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhereHas('persons', fn($q) => $q->search($search))
        );
    }
}
