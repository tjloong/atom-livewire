<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;

class Update extends Component
{
    public $page;
    public $component;
    
    /**
     * Mount
     */
    public function mount($id)
    {
        $this->page = get_model('Page')->findOrFail($id);

        breadcrumb($this->page->name);
    }

    /**
     * Get component name property
     */
    public function getComponentNameProperty()
    {
        if (file_exists(resource_path('views/livewire/app/page/' . $this->page->slug . '.blade.php'))) {
            return 'app.page.' . $this->page->slug;
        }
        else if (file_exists(resource_path('views/livewire/app/page/' . $this->page->slug . '/index.blade.php'))) {
            return 'app.page.' . $this->page->slug . '.index';
        }
        else {
            return 'atom.page.form';
        }    
    }
    
    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.page.update');
    }
}