<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;

class Onboarding extends Component
{
    public $tab;
    public $redirect;

    protected $listeners = ['next'];
    protected $queryString = ['redirect'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->tab = $this->tab ?? data_get(collect($this->tabs)
            ->filter(fn($val) => !empty(data_get($val, 'slug')))
            ->first()
        , 'slug');
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty(): array
    {
        return [];
    }

    /**
     * Get is onboarded property
     */
    public function getIsOnboardedProperty(): bool
    {
        return count(session('onboarding', [])) === count($this->tabs);
    }

    /**
     * Next
     */
    public function next(): mixed
    {
        session([
            'onboarding' => collect(session('onboarding'))
                ->push($this->tab)
                ->unique()
                ->toArray(),
        ]);

        if ($next = $this->getNextTab()) {
            return redirect()->route('app.onboarding', [
                'tab' => $next,
                'redirect' => $this->redirect,
            ]);
        }

        return $this->completed();
    }

    /**
     * Completed
     */
    public function completed(): void
    {
        if (!user('onboarded_at')) user()->fill(['onboarded_at' => now()])->save();
    }

    /**
     * Close
     */
    public function close(): mixed
    {
        session()->forget('onboarding');
        return redirect(user()->home());
    }

    /**
     * Get next tab
     */
    public function getNextTab()
    {
        return collect($this->tabs)->pluck('slug')->filter()
            ->filter(fn($slug) => !in_array($slug, session('onboarding', [])))
            ->first();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.onboarding');
    }
}