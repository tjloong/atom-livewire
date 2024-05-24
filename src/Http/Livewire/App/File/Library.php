<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Component;

class Library extends Component
{
    public $paginator;

    public $filters = [
        'search' => null,
    ];

    public $config = [
        'accept' => '*',
        'multiple' => false,
    ];

    protected $listeners = [
        'showFilesLibrary' => 'open',
    ];

    // open
    public function open($config = []) : void
    {
        $this->config = [...$this->config, ...$config];
        $this->paginator = model('file')->filter($this->filters)->latest()->paginate(50);
        $this->modal();
    }
}