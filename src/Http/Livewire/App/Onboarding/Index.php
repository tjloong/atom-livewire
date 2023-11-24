<?php

namespace Jiannius\Atom\Http\Livewire\App\Onboarding;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $tab;
    public $redirect;

    protected $listeners = ['next'];
    protected $queryString = ['redirect'];

    // mount
    public function mount()
    {
        if (!$this->tabs) return $this->reload();
        if (!session()->has('onboarding')) session(['onboarding' => 0]);

        $this->tab = collect($this->tabs)->pluck('slug')->filter()->values()->get(session('onboarding'));
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

    // get is completed property
    public function getIsCompletedProperty() : bool
    {
        return !$this->tabs || session('onboarding') >= count($this->tabs);
    }

    // updated tab
    public function updatedTab($val) : mixed
    {
        session(['onboarding' => collect($this->tabs)->where('slug', $val)->keys()->first()]);

        return $this->reload();
    }

    // next
    public function next() : mixed
    {
        session()->increment('onboarding');

        return $this->reload();
    }

    // reload
    public function reload() : mixed
    {
        return $this->isCompleted
            ? to_route('app.onboarding.completed', ['redirect' => $this->redirect])
            : to_route('app.onboarding', ['redirect' => $this->redirect]);
    }
}