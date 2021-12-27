<?php

namespace App\Http\Livewire\App\Blog;

use App\Models\Blog;
use Livewire\Component;

class Update extends Component
{
    public $tab = 'content';
    public Blog $blog;

    protected $listeners = ['saved'];
    protected $queryString = ['tab'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.blog.update');
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