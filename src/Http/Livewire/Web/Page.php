<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;
use Jiannius\Atom\Models\Page as PageModel;

class Page extends Component
{
    public $view;
    public $page;

    /**
     * Mount
     * 
     * @return void
     */
    public function mount($slug)
    {
        if (view()->exists('livewire.web.pages.' . $slug)) $this->view = 'livewire.web.pages.' . $slug;
        else if ($page = PageModel::where('slug', $slug)->first()) {
            $this->view = 'atom::web.page';
            $this->page = $page;
        }
        else abort(404);
    }

    /**
     * Render component
     * 
     * @return void
     */
    public function render()
    {
        return view($this->view)->layout('layouts.web');
    }
}