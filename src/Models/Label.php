<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasLocale;
use Jiannius\Atom\Traits\Models\HasFilters;

class Label extends Model
{
    use HasFilters;
    use HasLocale;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'seq' => 'integer',
        'data' => 'array',
    ];

    protected $slugify = ['name.en' => 'slug'];

    // get children for label
    public function children()
    {
        return $this->hasMany(model('label'), 'parent_id');
    }

    // get parent for label
    public function parent()
    {
        return $this->belongsTo(model('label'), 'parent_id');
    }

    // attribute for parents
    protected function parents(): Attribute
    {
        return Attribute::make(
            get: function () {
                $parents = collect();
                $parent = $this->parent;

                while ($parent) {
                    $parents->push($parent);
                    $parent = $parent->parent;
                }

                return $parents->reverse()->values();
            }
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }
}
