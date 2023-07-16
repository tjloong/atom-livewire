<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    // mount
    public function mount()
    {
        if ($this->tab) {
            $tab = tabs($this->filteredTabs, $this->tab);
            if (!$tab || data_get($tab, 'enabled') === false) abort(404);
    
            $this->tab = data_get($tab, 'slug');    
        }
        elseif ($first = data_get(tabs($this->filteredTabs)->first(), 'slug')) {
            return redirect()->route('app.preferences', [$first]);
        }
    }

    // get title propert
    public function getTitleProperty(): string
    {
        return 'Preferences';
    }

    // get filtered tabs property
    public function getFilteredTabsProperty(): array
    {
        return collect($this->tabs)
            ->filter(fn($tab) => data_get($tab, 'enabled') !== false)
            ->filter(fn($tab) => data_get($tab, 'hidden') !== true)
            ->values()
            ->map(fn($tab) => ($children = data_get($tab, 'tabs'))
                ? array_merge($tab, [
                    'tabs' => collect($children)
                        ->filter(fn($tab) => data_get($tab, 'enabled') !== false)
                        ->filter(fn($tab) => data_get($tab, 'hidden') !== true)
                        ->values()
                        ->all(),
                ])
                : $tab
            )
            ->all();
    }

    // get tabs property
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'General', 'tabs' => [
                ['slug' => 'blog-category', 'label' => 'Blog Categories', 'livewire' => [
                    'name' => 'app.label',
                    'data' => ['type' => 'blog-category'],
                ]],
            ]],
        ];
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.preferences');
    }
}