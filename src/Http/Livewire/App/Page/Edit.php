<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $page;
    public $autosavedAt;

    protected $listeners = [
        'editPage' => 'open',
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
        return $this->page->slug ?? str($this->page->name)->slug();
    }

    // update page content
    public function updatedPageContent() : void
    {
        $this->page->save();
        $this->autosavedAt = now();
    }

    // open
    public function open($ulid) : void
    {
        if ($this->page = model('page')->where('ulid', $ulid)->first()) {
            $this->overlay();
        }
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->page->save();
        $this->overlay(false);
    }
}