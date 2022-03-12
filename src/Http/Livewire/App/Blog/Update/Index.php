<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $tabs;
    public $blog;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($id, $tab = null)
    {
        $this->blog = model('blog')->findOrFail($id);
        $this->tabs = config('atom.blogs.sidenavs');

        if (!$tab) return redirect()->route('app.blog.update', [$id, head(array_keys($this->tabs))]);
        else $this->tab = $tab;

        breadcrumbs()->push($this->blog->title);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->blog->delete();

        session()->flash('flash', 'Blog Deleted');
        
        return redirect()->route('app.blog.listing');
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Blog Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.blog.update.index');
    }
}