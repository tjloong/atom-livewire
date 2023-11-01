<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Jiannius\Atom\Component;

class Page extends Component
{
    public $slug;
    public $page;

    // mount
    public function mount()
    {
        $pages = model('page')->where('slug', $this->slug);

        $this->page = $pages->count() > 1
            ? $pages->where('locale', app()->currentLocale())->first()
            : $pages->firstOrFail();
    }
}