<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public $slug;
    public $preview;
    public $filters;

    protected $queryString = [
        'filters',
    ];

    protected $listeners = ['refresh' => '$refresh'];

    // mount
    public function mount() : void
    {
        $this->preview = user() && request()->query('preview');

        if ($this->blog) seo($this->blog->getSeo());

        if (!$this->filters) {
            $this->fill([
                'filters.search' => null,
                'filters.labels' => [],
            ]);
        }
    }

    // get blog property
    public function getBlogProperty() : mixed
    {
        if (!$this->slug) return null;
        if (data_get($this->filters, 'search')) return null;
        if (data_get($this->filters, 'labels')) return null;

        $blogs = model('blog')
            ->when(!$this->preview, fn($q) => $q->status('published'))
            ->where('slug', $this->slug)
            ->latest()
            ->get();

        if ($blogs->count() > 1) return $blogs->where('locale', app()->currentLocale())->first();
        else return $blogs->first();
    }

    // get blogs property
    public function getBlogsProperty() : mixed
    {
        if ($this->blog) return null;

        return model('blog')
            ->status('published')
            ->when(data_get($this->filters, 'search'), fn($q, $search) => $q->search($search))
            ->when(data_get($this->filters, 'labels'), fn($q, $labels) => $q
                ->whereHas('labels', fn($q) => $q->whereIn('labels.slug', $labels))
            )
            ->latest('published_at')
            ->latest()
            ->paginate(50);
    }

    // get recents property
    public function getRecentsProperty() : mixed
    {
        $recents = model('blog')
            ->status('published')
            ->when($this->slug, fn($q) => $q->where('slug', '<>', $this->slug))
            ->latest('published_at')
            ->latest()
            ->take(6)
            ->get();

        if (!$recents->count()) return null;

        return $recents;
    }

    // get related property
    public function getRelatedProperty() : mixed
    {
        if (!$this->blog) return null;

        $labels = $this->blog->labels->pluck('id')->unique()->values()->all();
        $related = model('blog')
            ->status('published')
            ->whereHas('labels', fn($q) => $q->whereIn('labels.id', $labels))
            ->where('blogs.id', '<>', $this->blog->id)
            ->latest('published_at')
            ->latest()
            ->take(6)
            ->get();

        if (!$related->count()) return null;

        return $related;
    }

    // get topics property
    public function getTopicsProperty() : mixed
    {
        $topics = model('label')
            ->readable()
            ->where('type', 'blog-category')
            ->oldest('seq')
            ->orderBy('name')
            ->get();
        
        if (!$topics->count()) return null;

        return $topics;
    }

    // get sidebar property
    public function getSidebarProperty() : array
    {
        return array_filter([
            'topics' => $this->topics,
            'recents' => $this->recents,
            'related' => $this->related,
        ]);
    }
}