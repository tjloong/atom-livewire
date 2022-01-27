<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Label;
use Jiannius\Atom\Models\Blog as BlogModel;

class Blog extends Component
{
    use WithPagination;

    public $blog;
    public $label;
    public $recents;
    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($slug = null)
    {
        $preview = auth()->user() && request()->query('preview');

        if ($slug) {
            $this->label = Label::where('type', 'blog-category')->where('slug', $slug)->first();

            if (!$this->label) {
                $this->blog = BlogModel::query()
                    ->when(!$preview, fn($q) => $q->status('published'))
                    ->where('slug', $slug)
                    ->firstOrFail();
    
                $this->recents = BlogModel::query()
                    ->status('published')
                    ->where('slug', '<>', $slug)
                    ->latest()
                    ->take(8)
                    ->get();
            }
        }
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        $labels = Label::query()
            ->where('type', 'blog-category')
            ->when($this->label, fn($q) => $q->where('id', '<>', $this->label->id))
            ->orderBy('seq')
            ->orderBy('name')
            ->get();

        $blogs = $this->blog
            ? null
            : BlogModel::query()
                ->status('published')
                ->when($this->search, fn($q) => $q->search($this->search))
                ->when($this->label, fn($q) => $q
                    ->whereHas('labels', fn($q) => $q->where('labels.id', $this->label->id))
                )
                ->paginate(30);

        return view('atom::web.blog', [
            'labels' => $labels,
            'blogs' => $blogs,
            'sidebar' => ($this->recents && $this->recents->count()) || $this->label || $labels->count(),
        ])->layout('layouts.web');
    }
}