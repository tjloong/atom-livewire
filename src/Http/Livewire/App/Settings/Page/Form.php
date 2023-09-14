<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Page;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithFileInput;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Form extends Component
{
    use WithForm;
    use WithFileInput;
    use WithPopupNotify;
    
    public $page;
    public $autosavedAt;

    // validation
    protected function validation(): array
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

    // update page content
    public function updatedPageContent(): void
    {
        $this->page->save();
        $this->autosavedAt = now();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();
        $this->page->save();
        $this->popup('Page Updated.');
        $this->emit('pageSaved');
    }
}