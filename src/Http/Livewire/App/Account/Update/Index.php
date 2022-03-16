<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $account;

    /**
     * Mount
     */
    public function mount($id)
    {
        $this->account = model('account')->findOrFail($id);

        if (!$this->tab) {
            $nav = $this->navs->first();
            $tab = isset($nav->tabs) ? $nav->tabs->first()->slug : $nav->slug;

            return redirect()->route('app.account.update', [$id, $tab]);
        }

        breadcrumbs()->push($this->account->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['slug' => 'overview'],
        ];
    }

    /**
     * Get navs property
     */
    public function getNavsProperty()
    {
        return collect(json_decode(json_encode($this->tabs)))
            ->map(function($nav) {
                if (isset($nav->tabs)) $nav->tabs = collect($nav->tabs);
                return $nav;
            })
            ->filter(fn($nav) => !isset($nav->tabs) || !empty($nav->tabs));
    }

    /**
     * Block
     */
    public function block()
    {
        $this->account->block();
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->account->unblock();
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->account->delete();
        
        session()->flash('flash', 'Account Deleted');

        return redirect()->route('app.account.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.index', ['navs' => $this->navs]);
    }
}