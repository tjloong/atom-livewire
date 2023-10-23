<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;
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
            get: fn() => !$this->published_at || $this->published_at->isFuture()
                ? enum('blog.status', 'DRAFT')
                : enum('blog.status', 'PUBLISHED'),
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
            ->where('title', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        $query
            ->when($status === enum('blog.status', 'DRAFT'), fn($q) => $q->whereNull('published_at'))
            ->when($status === enum('blog.status', 'PUBLISHED'), fn($q) => $q->whereNotNull('published_at'));
    }
}
