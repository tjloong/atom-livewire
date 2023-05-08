<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Livewire\Component;

class Create extends Component
{
    public $blog;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->blog = model('blog');

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return 'Create Blog';
    }

    /**
     * Submitted
     */
    public function submitted($id): mixed
    {
        return redirect()->route('app.blog.update', [$id]);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.blog.create');
    }
}