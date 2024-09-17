<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\Seo;

class Page extends Model
{
    use HasFactory;
    use HasFilters;
    use HasSlug;
    use HasUlids;
    use Seo;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    // scope for search
    public function scopeSearch($query, $search): void
    {
        $query->whereAny(['name', 'title', 'slug'], 'like', "%$search%");
    }

    // get all slugs
    public function getSlugs(): array
    {
        $slugs = [];
        $dir = resource_path('views/livewire/web/pages');
        
        $pages = model('page')->get();
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
