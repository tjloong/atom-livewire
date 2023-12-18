<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $pageId;

    protected $listeners = [
        'setPageId',
        'updatePage' => 'setPageId',
    ];

    // set page id
    public function setPageId($id = null) : void
    {
        $this->fill(['pageId' => $id ?: null]);
    }
}