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
    public function mount($tab = null)
    {
        if (!$tab) return redirect()->route('blog.update', [$this->blog->id, 'content']);

        $this->tab = $tab;
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.blog.update');
    }

    /**
     * Delete blog
     * 
     * @return void
     */
    public function delete()
    {
        $this->blog->delete();
        session()->flash('flash', 'Blog Deleted');
        return redirect()->route('blog.listing');
    }

    /**
     * Saved blog handler
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Blog Updated', 'type' => 'success']);
    }
}