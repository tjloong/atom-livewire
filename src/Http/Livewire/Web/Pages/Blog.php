<?php

namespace Jiannius\Atom\Http\Livewire\Web\Pages;

use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public $search;
    public $filters;
    public $preview;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->preview = auth()->user() && request()->query('preview');
        $this->filters = array_filter([optional(model('label')->where('slug', $this->slug)->first())->slug]);

        if ($this->blog) {
            config([
                'atom.seo.title' => $this->blog->seo->title ?? $this->blog->title,
                'atom.seo.description' => $this->blog->seo->description ?? html_excerpt($this->blog->excerpt ?? $this->blog->content),
                'atom.seo.image' => $this->blog->seo->image ?? $this->blog->cover->url ?? null,
            ]);
        }
    }

    /**
     * Get slug property
     */
    public function getSlugProperty()
    {
        $slug = request()->route('slug');

        if ($slug === 'blog' || $slug === 'blog/') return null;

        $split = explode('/', $slug);

        return end($split);
    }

    /**
     * Get blog property
     */
    public function getBlogProperty()
    {
        if ($this->filters) return;

        return model('blog')
            ->when(!$this->preview, fn($q) => $q->status('published'))
            ->where('slug', $this->slug)
            ->first();
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
            ->paginate(30);
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
        return view('atom::web.pages.blog')->layout('layouts.web');
    }
}