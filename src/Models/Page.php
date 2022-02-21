<?php

namespace Jiannius\Atom\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasFilters;

class Page extends Model
{
    use HasSlug;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'seo' => 'object',
        'data' => 'object',
    ];

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
            ->orWhere('title', 'like', "%$search%")
            ->orWhere('slug', 'like', "%$search%")
        );
    }

    /**
     * Get all slugs
     */
    public static function getSlugs()
    {
        $slugs = [];
        $dir = resource_path('views/livewire/web/pages');
        
        $pages = Schema::hasTable((new self)->getTable()) ? self::all() : [];
        $views = file_exists($dir) ? File::allFiles($dir) : [];
        
        foreach ($views as $view) {
            array_push($slugs, str_replace('.blade.php', '', $view->getFilename()));
        }
    
        foreach ($pages as $page) {
            array_push($slugs, $page->slug);
        }

        return array_filter($slugs);
    }
}
