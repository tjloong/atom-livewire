<?php

namespace Jiannius\Atom\Http\Livewire\App\Tenant;

use Livewire\Component;

class Create extends Component
{
    public $tenant;
    public $onboarding;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->tenant = model('tenant')->fill([
            'country' => tenant()
                ? tenant('country')
                : (user('data.signup.geo.iso_code') ?? 'MY'),
        ]);
    }

    /**
     * Submitted
     */
    public function submitted($id): mixed
    {
        $tenant = model('tenant')->find($id);

        if (!tier('root')) {
            $tenant->setOwner(user());
            $tenant->setPreferred(user());
            $tenant->setup();
        }

        return tier('root')
            ? redirect()->route('app.tenant.update', [$tenant->id])
            : redirect()->route('app.onboarding');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tenant.create');
    }
}