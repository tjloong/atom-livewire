<?php

namespace Jiannius\Atom\Http\Livewire\App\Page;

use Livewire\Component;

class Update extends Component
{
    public $page;
    
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
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.page.update');
    }
}