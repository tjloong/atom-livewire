<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasFilters;

class ContactPerson extends Model
{
    use HasFactory;
    use HasFilters;

    protected $table = 'contact_persons';
    
    protected $guarded = [];

    protected $casts = [
        'contact_id' => 'integer',
    ];

    /**
     * Get contact for contact person
     */
    public function contact()
    {
        return $this->belongsTo(get_class(model('contact')));
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
            ->orWhere('designation', 'like', "%$search%")
        );
    }
}
