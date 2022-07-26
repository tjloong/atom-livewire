<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasLocale;
use Jiannius\Atom\Traits\HasFilters;

class Label extends Model
{
    use HasSlug;
    use HasLocale;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'name' => 'object',
        'seq' => 'integer',
        'data' => 'object',
    ];

    protected $slugify = ['name.en' => 'slug'];

    /**
     * Get children for label
     */
    public function children()
    {
        return $this->hasMany(get_class(model('label')), 'parent_id');
    }

    /**
     * Get parent for label
     */
    public function parent()
    {
        return $this->belongsTo(get_class(model('label')), 'parent_id');
    }

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }
}
