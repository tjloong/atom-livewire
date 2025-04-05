<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\Slugify;
use Jiannius\Atom\Traits\Models\HasLocale;
use Jiannius\Atom\Traits\Models\HasSequence;

class Label extends Model
{
    use Footprint;
    use HasLocale;
    use Slugify;
    use HasSequence;

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'seq' => 'integer',
        'data' => 'array',
        'is_locked' => 'boolean',
    ];

    protected $appends = [
        'name_locale',
        'color_value',
        'color_value_inverted',
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

    // attribute for name locale
    protected function nameLocale() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->locale('name'),
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

    // attribute for color value
    protected function colorValue() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->color ? color($this->color)->value() : null,
        );
    }

    // attribute for color value inverted
    protected function colorValueInverted() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->color ? color($this->color)->inverted()->value() : null,
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

    // to string
    public function __toString()
    {
        return get($this->name, app()->currentLocale());
    }

    // get the badge
    public function badge() : array
    {
        return [
            'color' => $this->color ?? 'gray',
            'label' => $this->locale('name'),
        ];
    }

    // get labels by type
    public function getType($type) : mixed
    {
        return model('label')->where('type', $type)->sequence()->get();
    }
}
