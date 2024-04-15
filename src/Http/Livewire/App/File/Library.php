<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Library extends Component
{
    use WithTable;

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

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('file')->latest();
    }

    // open
    public function open($config = []) : void
    {
        $this->config = [
            ...$this->config,
            ...$config,
        ];

        $this->openDrawer();
    }
}