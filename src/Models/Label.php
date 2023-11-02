<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'is_locked' => 'boolean',
    ];

    protected $slugify = ['name.en' => 'slug'];

    // get image for label
    public function image() : BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }
    
    // get parent for label
    public function parent() : BelongsTo
    {
        return $this->belongsTo(model('label'), 'parent_id');
    }

    // get children for label
    public function children() : HasMany
    {
        return $this->hasMany(model('label'), 'parent_id');
    }

    // attribute for color class
    protected function colorClass() : Attribute
    {
        return Attribute::make(
            get: fn() => [
                'gray' => 'bg-gray-500',
                'red' => 'bg-red-500',
                'orange' => 'bg-orange-500',
                'yellow' => 'bg-yellow-500',
                'green' => 'bg-green-500',
                'cyan' => 'bg-cyan-500',
                'blue' => 'bg-blue-500',
                'purple' => 'bg-purple-500',
                'black' => 'bg-black',
                'white' => 'bg-white',
            ][$this->color] ?? null,
        );
    }

    // attribute for parents
    protected function parents() : Attribute
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
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->whereRaw('lower(`name`) like ?', ['%'.str()->lower($search).'%'])
            ->orWhereRaw('`slug` like ?', ['%'.str()->lower($search).'%'])
        );
    }
}
