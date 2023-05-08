<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Blog extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'seo' => 'object',
        'cover_id' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $slugify = ['title' => 'slug'];

    /**
     * Get cover for blog
     */
    public function cover(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'cover_id');
    }

    /**
     * Get labels for blog
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(model('label'), 'blog_labels');
    }

    /**
     * Get status for blog
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => !$this->published_at || $this->published_at->isFuture()
                ? 'draft'
                : 'published',
        );
    }

    /**
     * Get seo for blog
     */
    protected function seo(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $seo = json_decode($value);

                return [
                    'title' => data_get($seo, 'title') ?? $this->title,
                    'description' => data_get($seo, 'description') ?? str($this->excerpt ?? strip_tags($this->content))->limit(255)->toString(),
                    'image' => data_get($seo, 'image') ?? optional($this->cover)->url,
                ];
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('title', 'like', "%$search%")
            ->orWhere('excerpt', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        $query
            ->when($status === 'draft', fn($q) => $q->whereNull('published_at'))
            ->when($status === 'published', fn($q) => $q->whereNotNull('published_at'));
    }
}
