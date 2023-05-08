<?php

namespace Jiannius\Atom\Http\Livewire\App\Tenant;

use Livewire\Component;

class Switcher extends Component
{
    /**
     * Get tenants property
     */
    public function getTenantsProperty(): mixed
    {
        $tenants = session('tenants') ?? user()->tenants;

        if (!session('tenants')) session(['tenants' => $tenants]);

        return $tenants;
    }

    /**
     * Switch
     */
    public function switch($id)
    {
        $tenant = model('tenant')->find($id);
        $tenant->setPreferred(user());

        session()->forget('can');
        
        return redirect(user()->home());
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tenant.switcher');
    }
}