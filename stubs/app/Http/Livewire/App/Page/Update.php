<?php

namespace App\Http\Livewire\App\Page;

use App\Models\Page;
use Livewire\Component;

class Update extends Component
{
    public $tab = 'content';
    public Page $page;

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
        return view('livewire.app.page.update');
    }

    /**
     * Handler after save
     * 
     * @return void
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => 'Page Updated', 'type' => 'success']);
    }
}