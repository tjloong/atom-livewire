<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;

class Update extends Component
{
    public $page;
    public $component;
    
    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($id)
    {
        $this->page = get_model('Page')->findOrFail($id);
        $this->getComponent();
    }
    
    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.page.update');
    }

    /**
     * Get form component
     * 
     * @return void
     */
    public function getComponent()
    {
        if (file_exists(resource_path('views/livewire/app/page/' . $this->page->slug . '.blade.php'))) {
            $this->component = 'app.page.' . $this->page->slug;
        }
        else if (file_exists(resource_path('views/livewire/app/page/' . $this->page->slug . '/index.blade.php'))) {
            $this->component = 'app.page.' . $this->page->slug . '.index';
        }
        else {
            $this->component = 'atom.page.form';
        }
    }
}