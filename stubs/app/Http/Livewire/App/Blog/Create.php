<?php

namespace App\Http\Livewire\App\Blog;

use App\Models\Blog;
use Livewire\Component;

class Create extends Component
{
    public $blog;
    public $back;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->back = request()->query('back');
        $this->blog = new Blog();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.blog.create');
    }

    /**
     * Saved action
     * 
     * @return void
     */
    public function saved($id)
    {
        session()->flash('flash', 'Blog created::success');
        return redirect()->route('blog.update', [$id, 'back' => $this->back]);
    }
}