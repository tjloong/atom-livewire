<?php

namespace App\Http\Livewire\App\BlogCategory;

use App\Models\Label;
use Livewire\Component;

class Create extends Component
{
    public $label;

    protected $listeners = ['saved'];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->label = new Label(['type' => 'blog-category']);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.blog-category.create');
    }

    /**
     * After saved
     * 
     * @return void
     */
    public function saved()
    {
        session()->flash('flash', 'Blog Category Created::success');
        return redirect()->route('blog-category.listing');
    }
}