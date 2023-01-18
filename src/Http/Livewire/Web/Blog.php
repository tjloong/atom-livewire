<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public $slug;
    public $search;
    public $labels;
    public $preview;

    protected $queryString = [
        'search' => ['except' => ''],
        'labels' => ['except' => ''],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->preview = auth()->user() && request()->query('preview');
        $this->setSeo();
    }

    /**
     * Get blog property
     */
    public function getBlogProperty()
    {
        if (!$this->slug) return;

        $blogs = model('blog')
            ->when(!$this->preview, fn($q) => $q->status('published'))
            ->where('slug', $this->slug)
            ->latest()
            ->get();

        if ($blogs->count() > 1) return $blogs->where('locale', app()->currentLocale())->first();
        else return $blogs->first();
    }

    /**
     * Get blogs property
     */
    public function getBlogsProperty()
    {
        if ($this->blog) return;

        return model('blog')
            ->status('published')
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->labels, fn($q) => $q
                ->whereHas('labels', fn($q) => $q->whereIn('labels.slug', explode(',', $this->labels)))
            )
            ->latest('published_at')
            ->latest()
            ->paginate(50);
    }

    /**
     * Get recents property
     */
    public function getRecentBlogsProperty()
    {
        return model('blog')
            ->status('published')
            ->when($this->slug, fn($q) => $q->where('slug', '<>', $this->slug))
            ->latest('published_at')
            ->latest()
            ->take(6)
            ->get();
    }

    /**
     * Get related property
     */
    public function getRelatedBlogsProperty()
    {
        if (!$this->blog) return;

        if ($id = $this->blog->labels->pluck('id')->unique()->values()->all()) {
            return model('blog')
                ->status('published')
                ->whereHas('labels', fn($q) => $q->whereIn('labels.id', $id))
                ->where('blogs.id', '<>', $this->blog->id)
                ->latest('published_at')
                ->latest()
                ->take(6)
                ->get();
        }
    }

    /**
     * Get topics property
     */
    public function getTopicsProperty()
    {
        return model('label')
            ->where('type', 'blog-category')
            ->orderBy('seq')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get show sidebar property
     */
    public function getShowSidebarProperty()
    {
        return $this->recents->count() || $this->labels->count();
    }

    /**
     * Set Seo
     */
    public function setSeo()
    {
        if (!$this->blog) return;

        config([
            'atom.seo.title' => $this->blog->seo->title ?? $this->blog->title,
            'atom.seo.description' => $this->blog->seo->description ?? html_excerpt($this->blog->excerpt ?? $this->blog->content),
            'atom.seo.image' => $this->blog->seo->image ?? $this->blog->cover->url ?? null,
        ]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('web.blog');
    }
}