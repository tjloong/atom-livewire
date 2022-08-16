<?php

namespace Jiannius\Atom\Http\Livewire\App\Onboarding;

use Livewire\Component;

class Index extends Component
{
    public $step;
    public $steps;
    public $redirect;

    protected $listeners = ['next'];
    protected $queryString = ['redirect'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->steps = auth()->user()->isAccountType('signup')
            ? collect($this->tabs)->map(fn($tab) => [
                'value' => data_get($tab, 'slug', $tab),
                'label' => data_get($tab, 'label') ?? str()->headline($tab),
                'completed' => false,
            ])
            : collect([]);

        $this->next();
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [];
    }

    /**
     * Next
     */
    public function next()
    {
        // update current step as completed
        if ($this->step) {
            $this->steps = $this->steps->map(fn($step) => $step['value'] === $this->step
                ? array_merge($step, ['completed' => true])
                : $step
            );
        }

        // go to next step
        $next = $this->steps->where('completed', false)->first();

        if ($next) $this->step = $next['value'];
        else return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return $this->redirect ?? route('app.onboarding.completed');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.onboarding.index');
    }
}