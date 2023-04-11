<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public $tab;

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->tab) {
            return redirect()->route('app.preferences', [$this->flatTabs->first()->get('slug')]);
        }

        if ($tab = $this->flatTabs->firstWhere('slug', $this->tab)) {
            breadcrumbs()->home($this->title);
            breadcrumbs()->push($tab->get('label'));
        }
        else abort(404);
    }

    /**
     * Get title propert
     */
    public function getTitleProperty(): string
    {
        return 'Preferences';
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'General', 'tabs' => [
                ['slug' => 'blog-category', 'label' => 'Blog Categories', 'livewire' => [
                    'name' => 'app.label.listing',
                    'data' => ['type' => 'blog-category'],
                ]],
            ]],
        ];
    }

    /**
     * Get flat tabs property
     */
    public function getFlatTabsProperty(): Collection
    {
        return collect($this->tabs)->pluck('tabs')->collapse()->filter()->map('collect');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.preferences');
    }
}