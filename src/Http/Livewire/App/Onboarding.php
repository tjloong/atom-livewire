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
    public function mount(): void
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
        return user()->status !== 'new';
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

        if (
            $next = collect($this->tabs)->pluck('slug')->filter()
                ->filter(fn($slug) => !in_array($slug, session('onboarding')))
                ->first()
        )  {
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
        session()->forget('onboarding');
        user()->fill(['onboarded_at' => now()])->save();
    }

    /**
     * Later
     */
    public function later(): mixed
    {
        session()->forget('onboarding');
        return redirect(user()->home());
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.onboarding');
    }
}