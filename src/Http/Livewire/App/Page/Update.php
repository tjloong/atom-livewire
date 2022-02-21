<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;
use Illuminate\Support\Str;

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
        $name = $this->page->slug ?? Str::slug($this->page->name);

        if (file_exists(resource_path('views/livewire/app/page/' . $name . '.blade.php'))) {
            return 'app.page.' . $name;
        }
        else if (file_exists(resource_path('views/livewire/app/page/' . $name . '/index.blade.php'))) {
            return 'app.page.' . $name . '.index';
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