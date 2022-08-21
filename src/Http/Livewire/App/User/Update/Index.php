<?php

namespace Jiannius\Atom\Http\Livewire\App\User\Update;

use Livewire\Component;

class Index extends Component
{
    public $tab = 'info';
    public $user;

    protected $queryString = ['tab'];

    /**
     * Mount
     */
    public function mount($user)
    {
        $this->user = model('user')
            ->withTrashed()
            ->findOrFail($user);

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $tabs = array_filter([
            ['slug' => 'info', 'label' => 'User Information'],
            enabled_module('permissions')
                ? ['slug' => 'permissions', 'label' => 'Permissions']
                : null,
        ]);

        return count($tabs) > 1 ? $tabs : [];
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
    public function delete($force = false)
    {
        if ($force) $this->user->forceDelete();
        else $this->user->delete();

        session()->flash('flash', 'User deleted');

        return redirect($this->redirectTo());
    }

    /**
     * Restore
     */
    public function restore()
    {
        $this->user->restore();

        session()->flash('flash', 'User Restored');

        return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('app.user.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.user.update.index');
    }
}