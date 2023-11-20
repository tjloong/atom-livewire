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
    public function mount() : void
    {
        $this->tab = $this->tab ?? $this->getNextTab();
        session(['onboarding' => []]);
    }

    // get tabs property
    public function getTabsProperty() : array
    {
        return [];
    }

    // get is completed property
    public function getisCompletedProperty() : bool
    {
        return count(session('onboarding', [])) === count($this->tabs);
    }

    // next
    public function next() : mixed
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
        else if ($this->isCompleted) {
            if (!user()->signup->onboarded_at) {
                user()->signup->fill(['onboarded_at' => now()])->save();
            }

            session(['onboarding' => []]);

            return to_route('app.onboarding.completed');
        }
    }

    // close
    public function close() : mixed
    {
        session(['onboarding' => 'onhold']);

        return redirect(user()->home());
    }

    // get next tab
    public function getNextTab()
    {
        $session = is_array(session('onboarding')) ? session('onboarding') : [];

        return collect($this->tabs)->pluck('slug')->filter()
            ->filter(fn($slug) => !in_array($slug, $session))
            ->first();
    }
}