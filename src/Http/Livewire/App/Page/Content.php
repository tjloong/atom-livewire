<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Content extends Component
{
    use WithForm;
    use WithFile;
    use WithPopupNotify;

    public $page;
    public $autosavedAt;
    
    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'page.title' => [
                'required' => 'Page title is required.',
                'string' => 'Page title must be string.',
                'max:255' => 'Page title is too long (Max 255 characters).',
            ],
            'page.slug' => ['required' => 'Slug is required.'],
            'page.locale' => ['nullable'],
            'page.content' => ['nullable'],
        ];
    }

    /**
     * Update page content
     */
    public function updatedPageContent(): void
    {
        $this->page->save();
        $this->autosavedAt = now();
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();
        $this->page->save();
        $this->popup('Page Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.page.content');
    }
}