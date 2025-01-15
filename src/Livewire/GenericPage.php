<?php

namespace Jiannius\Atom\Livewire;

use Livewire\Component;

class GenericPage extends Component
{
    public $slug;
    public $page;

    public function mount()
    {
        $pages = model('page')->where('slug', $this->slug);

        $this->page = $pages->count() > 1
            ? $pages->where('locale', app()->currentLocale())->first()
            : $pages->firstOrFail();
    }
}
