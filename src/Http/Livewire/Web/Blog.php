<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public $slug;
    public $search;
    public $filters;
    public $preview;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        $this->preview = auth()->user() && request()->query('preview');
        $this->filters = array_filter([optional(model('label')->where('slug', $this->slug)->first())->slug]);
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
            ->when($this->filters, fn($q) => $q
                ->whereHas('labels', fn($q) => $q->whereIn('labels.slug', $this->filters))
            )
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }

    /**
     * Get recents property
     */
    public function getRecentsProperty()
    {
        return model('blog')
            ->status('published')
            ->when($this->slug, fn($q) => $q->where('slug', '<>', $this->slug))
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }

    /**
     * Get labels property
     */
    public function getLabelsProperty()
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
     * Toggle filter
     */
    public function toggleFilter($slug)
    {
        if (in_array($slug, $this->filters)) $this->filters = collect($this->filters)->reject(fn($val) => $val === $slug)->values()->all();
        else array_push($this->filters, $slug);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::web.blog')->layout('layouts.web');
    }
}