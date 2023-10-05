<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Page;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $page;
    public $autosavedAt;

    protected $listeners = [
        'updatePage' => 'open',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'page.title' => [
                'required' => 'Page title is required.',
                'string' => 'Page title must be string.',
                'max:255' => 'Page title is too long (Max 255 characters).',
            ],
            'page.slug' => ['nullable'],
            'page.locale' => ['nullable'],
            'page.content' => ['nullable'],
        ];
    }

    // get slug property
    public function getSlugProperty() : string
    {
        return $this->page->slug 
            ?? str($this->page->name)->slug();
    }

    // update page content
    public function updatedPageContent() : void
    {
        $this->page->save();
        $this->autosavedAt = now();
    }

    // open
    public function open($id) : void
    {
        if ($this->page = model('page')->find($id)) {
            $this->dispatchBrowserEvent('page-update-open');
        }
    }

    // close
    public function close(): void
    {
        $this->dispatchBrowserEvent('page-update-close');
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->page->save();

        $this->emit('pageUpdated');
        $this->close();
    }
}