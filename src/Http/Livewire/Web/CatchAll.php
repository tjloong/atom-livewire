<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class CatchAll extends Component
{
    public $ref;
    public $slug;

    public $preventBot = [
        'contact-us',
    ];

    protected $queryString = ['ref'];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->page && !$this->livewire) abort(404);
    }

    /**
     * Get page property
     */
    public function getPageProperty()
    {
        if (!$this->slug) return;
        if (config('atom.static_site')) return;
        if (!enabled_module('pages')) return;

        $pages = model('page')->where('slug', $this->slug)->get();

        if ($pages->count() > 1) return $pages->where('locale', app()->currentLocale())->first();
        else return $pages->first();
    }

    /**
     * Get livewire property
     */
    public function getLivewireProperty()
    {
        return $this->slug ? atom_lw('web.'.$this->slug) : atom_lw('web');
    }

    /**
     * Render
     */
    public function render()
    {
        if (!$this->livewire && $this->page && ($view = atom_view('web.'.$this->page->slug))) return $view;
        else return atom_view('web.catchall');
    }
}