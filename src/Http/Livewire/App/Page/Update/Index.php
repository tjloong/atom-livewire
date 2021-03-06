<?php

namespace Jiannius\Atom\Http\Livewire\App\Page\Update;

use Livewire\Component;

class Index extends Component
{
    public $page;
    
    /**
     * Mount
     */
    public function mount($page)
    {
        $this->page = model('page')->findOrFail($page);

        breadcrumbs()->push($this->page->name);
    }

    /**
     * Get slug property
     */
    public function getSlugProperty()
    {
        return $this->page->slug ?? str($this->page->name)->slug();
    }
    
    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.page.update.index');
    }
}