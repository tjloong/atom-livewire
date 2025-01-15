<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $redirect;

    protected $listeners = ['next'];

    // mount
    public function mount()
    {
        $this->redirect = request()->query('redirect');

        if (!$this->tabs) return $this->next();
        if (!session()->has('onboarding')) $this->setSession();

        $this->setTab();
    }

    // get tabs property
    public function getTabsProperty() : array
    {
        return [];
    }

    // get selected tab property
    public function getSelectedTabProperty() : array
    {
        return $this->tab
            ? collect($this->tabs)->firstWhere('slug', $this->tab)
            : null;
    }

    // updated tab
    public function updatedTab($val) : void
    {
        $this->setSession($val);
    }

    // next
    public function next() : mixed
    {
        if ($this->tabs) $this->setSession();

        // completed
        if (!$this->tabs || session('onboarding') >= count($this->tabs)) {
            $this->setSession(false);
            return to_route('onboarding.completed', ['redirect' => $this->redirect]);
        }
        else {
            $this->setTab();
        }
    }

    // set tab
    public function setTab() : void
    {
        $tabs = collect($this->tabs)->pluck('slug')->filter()->values();
        $this->tab = session('onboarding') ? $tabs->get(session('onboarding')) : $tabs->first();
    }

    // set session
    public function setSession($tab = null) : void
    {
        if ($tab === false) session()->forget('onboarding');
        else if ($tab) session(['onboarding' => collect($this->tabs)->where('slug', $tab)->keys()->first()]);
        else if (is_numeric(session('onboarding'))) session()->increment('onboarding');
        else session(['onboarding' => 0]);
    }
}