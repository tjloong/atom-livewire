<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Livewire\Component;

class Update extends Component
{
    public $tab;
    public $user;

    /**
     * Mount
     */
    public function mount($userId = null)
    {
        $this->user = user()->isTier('root')
            ? model('user')->findOrFail($userId)
            : user();

        $this->tab = $this->tab ?? data_get($this->flatTabs->first(), 'slug');

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['group' => 'Account', 'tabs' => array_merge(
                [
                    ['slug' => 'info', 'label' => 'Sign-Up Information', 'livewire' => 'app.signup.info'],
                ],

                enabled_module('plans')
                    ? [
                        ['slug' => 'subscription', 'label' => 'Subscriptions', 'livewire' => 'app.billing.current-subscriptions'],
                        ['slug' => 'payment', 'label' => 'Payment History', 'livewire' => 'app.billing.payment.listing'],
                    ]
                    : [],
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
        $this->user->block();

        return redirect($this->redirectTo());
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->unblock();

        return redirect($this->redirectTo());
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();
        
        return redirect($this->redirectTo())->with('flash', 'Sign-Up Deleted');
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('app.signup.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.signup.update');
    }
}