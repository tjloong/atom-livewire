<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\System;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class User extends Component
{
    use WithPagination;
    use WithPopupNotify;
    use WithTable;

    public $account;
    public $sortBy = 'name';
    public $sortOrder = 'asc';
    public $filters = [
        'search' => null,
        'status' => null,
        'role_id' => null,
        'team_id' => null,
    ];
    
    protected $listeners = ['refresh' => '$refresh'];
        
    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('user')->when(
            $this->account && auth()->user()->isAccountType('root'), 
            fn($q) => $q->where('account_id', $this->account->id),
            fn($q) => $q->where('account_id', auth()->user()->account_id)
        );
    }

    /**
     * Get users property
     */
    public function getUsersProperty()
    {
        return $this->query
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Get trashed property
     */
    public function getTrashedProperty()
    {
        return $this->query->status('trashed')->count();
    }

    /**
     * Open
     */
    public function open($action, $id = null, $data = null)
    {
        $component = [
            'create' => lw('app.settings.system.user-form-modal'),
            'edit' => lw('app.settings.system.user-form-modal'),
            'permission' => lw('app.settings.system.permission-form-modal'),
        ][$action];

        $this->emitTo($component, 'open', $id, $data);
    }

    /**
     * Block
     */
    public function block($id)
    {
        $user = collect($this->users->items())->where('id', $id)->first();
        $user->block();

        $this->popup('User Blocked');
        $this->emitSelf('refresh');
    }

    /**
     * Unblock
     */
    public function unblock($id)
    {
        $user = collect($this->users->items())->where('id', $id)->first();
        $user->unblock();

        $this->popup('User Unblocked');
        $this->emitSelf('refresh');
    }

    /**
     * Delete
     */
    public function delete($id, $force = false)
    {
        $user = collect($this->users->items())->where('id', $id)->first();

        if ($force) $user->forceDelete();
        else $user->delete();
        
        $this->popup('User Deleted');
        $this->emitSelf('refresh');
    }

    /**
     * Restore
     */
    public function restore($id)
    {
        $user = collect($this->users->items())->where('id', $id)->first();
        $user->restore();
        
        $this->popup('User Restored');
        $this->emitSelf('refresh');
    }

    /**
     * Empty trashed
     */
    public function emptyTrashed()
    {
        (clone $this->query)->onlyTrashed()->forceDelete();

        $this->popup('Trash Cleared');
        $this->reset('filters');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.system.user');
    }
}