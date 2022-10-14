<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Livewire\Component;

class Create extends Component
{
    public $blog;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->blog = model('blog');
        breadcrumbs()->push('Create Blog');
    }

    /**
     * Save
     */
    public function saved($id)
    {
        session()->flash('flash', 'Blog created::success');
        return redirect()->route('app.blog.update', [$id]);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.blog.create');
    }
}