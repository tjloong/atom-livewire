<?php

namespace App\Http\Livewire\App\BlogCategory;

use App\Models\Label;
use Livewire\Component;

class Update extends Component
{
    public Label $label;

    protected $listeners = ['saved'];

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
        return view('livewire.app.blog-category.update');
    }

    /**
     * After saved
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Blog Category Updated', 'type' => 'success']);
    }

    /**
     * Delete blog category
     * 
     * @return void
     */
    public function delete()
    {
        $this->label->delete();
        session()->flash('flash', 'Blog Category Deleted');
        return redirect()->route('blog-category.listing');
    }
}