<?php

namespace Jiannius\Atom\Http\Livewire\Web;

use Jiannius\Atom\Component;

class Announcement extends Component
{
    public $slug;
    public $announcement;

    // mount
    public function mount() : void
    {
        $this->announcement = model('announcement')->findBySlugOrFail($this->slug);
        seo($this->announcement->seo);
    }
}