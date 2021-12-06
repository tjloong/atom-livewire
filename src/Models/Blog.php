<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasSlug;
    use HasOwner;
    use HasFilters;

    protected $fillable = [
        'title',
        'slug',
        'redirect_slug',
        'content',
        'seo',
        'cover_id',
        'published_at',
    ];

    protected $casts = [
        'seo' => 'object',
        'cover_id' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Get cover for blog
     */
    public function cover()
    {
        return $this->belongsTo(File::class, 'cover_id');
    }

    /**
     * Get labels for blog
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'blogs_labels', 'blog_id', 'label_id');
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

    /**
     * Get excerpt attribute
     * 
     * @return string
     */
    public function getExcerptAttribute()
    {
        $content = $this->content;
        $content = strip_tags($content);
        $content = html_entity_decode($content);
        $content = urldecode($content);
        $content = preg_replace('/[^A-Za-z0-9]/', ' ', $content);
        $content = preg_replace('/ +/', ' ', $content);
        $content = trim($content);
        $length = Str::length($content);

        if ($length > 120) return substr($content, 0, 120) . '...';
        else return $content;
    }
}
