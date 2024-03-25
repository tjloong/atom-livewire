<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jiannius\Atom\Traits\Models\Footprint;

class Blog extends Model
{
    use Footprint;
    use HasFilters;
    use HasSlug;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'seo' => 'array',
        'published_at' => 'datetime',
    ];

    // get cover for blog
    public function cover() : BelongsTo
    {
        return $this->belongsTo(model('file'), 'cover_id');
    }

    // get labels for blog
    public function labels() : BelongsToMany
    {
        return $this->belongsToMany(model('label'), 'blog_labels');
    }

    // attribute for status
    protected function status() : Attribute
    {
        return Attribute::make(
            get: fn() => enum('blog.status', pick([
                'UPCOMING' => $this->published_at && $this->published_at->gt(now()),
                'PUBLISHED' => !empty($this->published_at),
                'DRAFT' => true,
            ])),
        );
    }

    // attribute for seo
    protected function seo() : Attribute
    {
        return Attribute::make(
            get: fn($value) => [
                'title' => data_get($value, 'title') ?? $this->name,
                'description' => data_get($value, 'description') 
                    ?? str($this->description ?? strip_tags($this->content))->limit(255)->toString(),
                'image' => data_get($value, 'image') ?? optional($this->cover)->url,
            ],
        );
    }

    // scope for search
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }

    // scope for status
    public function scopeStatus($query, $status): void
    {
        if ($status) {
            $query->where(function($q) use ($status) {
                foreach ((array) $status as $value) {
                    $value = is_string($value) ? $value : $value->value;

                    if ($value === enum('blog.status', 'DRAFT')->value) {
                        $q->orWhereNull('blogs.published_at');
                    }
                    elseif ($value === enum('blog.status', 'UPCOMING')->value) {
                        $q->orWhereRaw('(blogs.published_at is not null and blogs.published_at > now())');
                    }
                    elseif ($value === enum('blog.status', 'PUBLISHED')->value) {
                        $q->orWhereRaw('(blogs.published_at is not null and blogs.published_at <= now())');
                    }
                }
            });
        }
    }

    // scope for label
    public function scopeLabel($query, $label) : void
    {
        if ($label) $query->whereHas('labels', fn($q) => $q->whereIn('blog_labels.label_id', (array) $label));
    }
}
