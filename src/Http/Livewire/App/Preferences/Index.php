<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Livewire\Component;

class Index extends Component
{
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
    public function getTitleProperty()
    {
        return 'Preferences';
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['group' => 'General', 'tabs' => [
                ['slug' => 'blog-categories', 'label' => 'Blog Categories', 'livewire' => [
                    'name' => 'app.preferences.label',
                    'data' => [
                        'type' => 'blog-category',
                        'sublabel' => false,
                    ],
                ]],
                ['slug' => 'tax', 'label' => 'Taxes', 'disabled' => !enabled_module('taxes')],
            ]],
        ];
    }

    /**
     * Get flat tabs property
     */
    public function getFlatTabsProperty()
    {
        return collect($this->tabs)->pluck('tabs')->collapse()->filter()->map('collect');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.preferences');
    }
}