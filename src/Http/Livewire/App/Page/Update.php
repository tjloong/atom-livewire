<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithForm;
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
            'page.slug' => ['nullable'],
            'page.locale' => ['nullable'],
            'page.content' => ['nullable'],
        ];
    }
    
    /**
     * Mount
     */
    public function mount($pageId): void
    {
        $this->page = model('page')->readable()->findOrFail($pageId);

        breadcrumbs()->push($this->page->name.(
            count(config('atom.locales')) > 1
                ? ' ('.$this->page->locale.')'
                : ''
        ));
    }

    /**
     * Get slug property
     */
    public function getSlugProperty(): string
    {
        return $this->page->slug ?? str($this->page->name)->slug();
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
        return atom_view('app.page.update');
    }
}