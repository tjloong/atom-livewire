<?php

namespace Jiannius\Atom\Http\Livewire\App\Account\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab;
    public $account;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($account = null)
    {
        $this->account = auth()->user()->isAccountType('root')
            ? model('account')->findOrFail($account)
            : auth()->user()->account;

        $this->tab = $this->tab ?? data_get($this->flatTabs->first(), 'slug');

        breadcrumbs()->push($this->account->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['group' => 'Account', 'tabs' => array_merge(
                [
                    ['slug' => 'register', 'label' => 'Registration', 'icon' => 'address-card'],
                ],

                enabled_module('plans')
                    ? [
                        ['slug' => 'subscription', 'label' => 'Subscriptions', 'icon' => 'bolt', 'livewire' => 'app.billing.current-subscriptions'],
                        ['slug' => 'payment', 'label' => 'Payment History', 'icon' => 'dollar-sign', 'livewire' => 'app.account-payment.listing'],
                    ]
                    : null,
            )],
        ];
    }

    /**
     * Get flat tabs property
     */
    public function getFlatTabsProperty()
    {
        return collect($this->tabs)->pluck('tabs')->collapse()->values();
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
        
        return redirect($this->redirectTo())->with('info', 'Account Deleted');
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
        return atom_view('app.account.update');
    }
}