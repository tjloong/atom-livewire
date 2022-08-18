<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Livewire\Component;

class CatchAll extends Component
{
    public $slug;

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
        return livewire_name(
            $this->slug
                ? 'web/'.$this->slug
                : 'web/index'
        );
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::web.catchall')->layout('layouts.web');
    }
}