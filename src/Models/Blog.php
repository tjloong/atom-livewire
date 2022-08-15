<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

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
    public function cover()
    {
        return $this->belongsTo(get_class(model('file')), 'cover_id');
    }

    /**
     * Get labels for blog
     */
    public function labels()
    {
        return $this->belongsToMany(get_class(model('label')), 'blog_labels');
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
            ->where('title', 'like', "%$search%")
            ->orWhere('excerpt', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     * 
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query
            ->when($status === 'draft', fn($q) => $q->whereNull('published_at'))
            ->when($status === 'published', fn($q) => $q->whereNotNull('published_at'));
    }

    /**
     * Get status for blog
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        if (!$this->published_at || $this->published_at->isFuture()) return 'draft';

        return 'published';
    }
}
