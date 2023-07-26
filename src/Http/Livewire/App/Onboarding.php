<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Jiannius\Atom\Component;

class Onboarding extends Component
{
    public $tab;
    public $redirect;

    protected $listeners = ['next'];
    protected $queryString = ['redirect'];

    // mount
    public function mount()
    {
        $this->tab = $this->tab ?? $this->getNextTab();
    }

    // get tabs property
    public function getTabsProperty(): array
    {
        return [];
    }

    // get is onboarded property
    public function getIsOnboardedProperty(): bool
    {
        return count(session('onboarding', [])) === count($this->tabs);
    }

    // next
    public function next(): mixed
    {
        session([
            'onboarding' => collect(session('onboarding'))
                ->push($this->tab)
                ->unique()
                ->toArray(),
        ]);

        if ($next = $this->getNextTab()) {
            return to_route('app.onboarding', [
                'tab' => $next,
                'redirect' => $this->redirect,
            ]);
        }

        return $this->completed();
    }

    // completed
    public function completed(): mixed
    {
        if (!user('onboarded_at')) user()->fill(['onboarded_at' => now()])->save();

        return $this->close();
    }

    // close
    public function close(): mixed
    {
        session()->forget('onboarding');

        return redirect(user()->home());
    }

    // get next tab
    public function getNextTab()
    {
        return collect($this->tabs)->pluck('slug')->filter()
            ->filter(fn($slug) => !in_array($slug, session('onboarding', [])))
            ->first();
    }
}