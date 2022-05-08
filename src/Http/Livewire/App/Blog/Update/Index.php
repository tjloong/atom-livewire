<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'content';
    public $blog;

    protected $listeners = ['saved'];
    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($blog)
    {
        $this->blog = model('blog')->findOrFail($blog);

        breadcrumbs()->push($this->blog->title);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['slug' => 'content', 'label' => 'Blog Content'],
            ['slug' => 'seo', 'label' => 'SEO'],
            ['slug' => 'settings', 'label' => 'Settings'],
        ];
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