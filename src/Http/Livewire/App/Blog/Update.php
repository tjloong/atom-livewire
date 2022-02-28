<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Livewire\Component;
use Jiannius\Atom\Models\Blog;

class Update extends Component
{
    public $tab;
    public Blog $blog;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        if (!$this->tab) return redirect()->route('blog.update', [$this->blog->id, 'content']);

        breadcrumbs()->push($this->blog->title);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->blog->delete();
        session()->flash('flash', 'Blog Deleted');
        return redirect()->route('blog.listing');
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
        return view('atom::app.blog.update');
    }
}