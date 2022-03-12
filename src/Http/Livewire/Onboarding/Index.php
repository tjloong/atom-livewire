<?php

namespace Jiannius\Atom\Http\Livewire\Onboarding;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $tabs;
    public $account;
    public $redirect;

    protected $listeners = ['next'];
    protected $queryString = ['redirect'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->account = auth()->user()->account;
        
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
            $this->account->onboard();
            return redirect($this->redirectTo());
        }
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return $this->redirect ?? route('onboarding.completed');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::onboarding.index')->layout('layouts.onboarding');
    }
}