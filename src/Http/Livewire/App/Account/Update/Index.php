<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $account;
    public $isHome;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($account = null)
    {
        $this->isHome = current_route('app.account.home');

        $this->account = !$this->isHome && auth()->user()->isAccountType('root')
            ? model('account')->findOrFail($account)
            : auth()->user()->account;

        $this->tab = $this->tab ?? $this->getFirstTab();

        if ($this->isHome) breadcrumbs()->flush();
        else  breadcrumbs()->push($this->account->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        // my account
        if ($this->isHome) {
            return [
                ['icon' => 'arrow-right-to-bracket', 'slug' => 'login', 'label' => 'Login Information'],
                ['icon' => 'lock', 'slug' => 'password', 'label' => 'Change Password'],
                enabled_module('plans') && auth()->user()->isAccountType('signup')
                    ? ['icon' => 'file-invoice-dollar', 'slug' => 'billing', 'label' => 'Billing', 'href' => route('app.billing.home')]
                    : null,
            ];
        }
        // update account
        else {
            return [
                ['icon' => 'address-card', 'slug' => 'register', 'label' => 'Registration Overview'],
                enabled_module('plans')
                    ? ['icon' => 'file-invoice-dollar', 'slug' => 'billing', 'label' => 'Billing']
                    : null,
            ];
        }
    }

    /**
     * Get first tab
     */
    public function getFirstTab()
    {
        $slugs = [];

        collect($this->tabs)->each(function($tab) use (&$slugs) {
            if ($children = data_get($tab, 'tabs')) $slugs = array_merge($slugs, collect($children)->pluck('slug')->toArray());
            else array_push($slugs, data_get($tab, 'slug'));
        });

        return head($slugs);
    }

    /**
     * Block
     */
    public function block()
    {
        $this->account->block();

        return redirect($this->redirectTo());
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->account->unblock();

        return redirect($this->redirectTo());
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->account->delete();
        
        session()->flash('flash', 'Account Deleted');

        return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('app.account.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.account.update.index');
    }
}