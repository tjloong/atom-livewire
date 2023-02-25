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
        $this->user = tier('root')
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
            ['group' => 'Account', 'tabs' => [
                ['slug' => 'info', 'label' => 'Sign-Up Information', 'livewire' => 'app.signup.info'],
            ]],
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

        return breadcrumbs()->back();
    }

    /**
     * Unblock
     */
    public function unblock()
    {
        $this->user->unblock();

        return breadcrumbs()->back();
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->user->delete();
        
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.signup.update');
    }
}