<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $tabs;
    public $signup;
    public $tenant;

    protected $listeners = ['next'];

    /**
     * Mount
     */
    public function mount()
    {
        if (enabled_module('signups')) $this->signup = auth()->user()->signup;
        if (enabled_module('tenants')) $this->tenant = auth()->user()->tenant;
        
        $this->tabs = collect(config('atom.onboarding.steps', []))->map(fn($label, $key) => [
            'value' => $key,
            'label' => $label,
            'completed' => false,
        ]);

        $this->next();
    }

    /**
     * Next
     */
    public function next()
    {
        // update current tab as completed
        if ($this->tab) {
            $this->tabs = $this->tabs->map(fn($tab) => $tab['value'] === $this->tab
                ? array_merge($tab, ['completed' => true])
                : $tab
            );
        }

        // go to next tab
        $next = $this->tabs->where('completed', false)->first();

        if ($next) $this->tab = $next['value'];
        else {
            optional($this->signup)->onboard();
            optional($this->tenant)->onboard();
            
            return redirect()->route('onboarding.completed');
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::onboarding.index')->layout('layouts.onboarding');
    }
}