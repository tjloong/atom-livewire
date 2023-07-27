<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Page;

use Jiannius\Atom\Component;

class Update extends Component
{
    public $page;
    public $autosavedAt;

    protected $listeners = [
        'open',
        'pageSaved' => 'close',
    ];

    // open
    public function open($id): void
    {
        $this->page = model('page')->readable()->findOrFail($id);

        $this->dispatchBrowserEvent('page-update-open');
    }

    // close
    public function close(): void
    {
        $this->emit('pageUpdateClosed');
        $this->dispatchBrowserEvent('page-update-close');
    }

    // get slug property
    public function getSlugProperty(): string
    {
        return $this->page->slug ?? str($this->page->name)->slug();
    }
}