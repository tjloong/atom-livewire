<?php

namespace Jiannius\Atom\Http\Livewire\App\Invitation;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

    public $invitation;

    /**
     * Mount
     */
    public function mount($invitationId): void
    {
        $this->invitation = model('invitation')->readable()->find($invitationId);

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return $this->invitation->email.'\'s Invitation';
    }

    /**
     * Get permissions property
     */
    public function getPermissionsProperty(): mixed
    {
        if (!enabled_module('permissions')) return null;

        return model('permission')->getPermissionList();
    }

    /**
     * Toggle
     */
    public function toggle($permission): void
    {
        $permissions = collect(data_get($this->invitation->data, 'permissions'));

        if ($permissions->contains($permission)) $permissions = $permissions->reject($permission)->values();
        else $permissions->push($permission);

        $this->invitation->fill([
            'data' => array_merge((array)$this->invitation->data, [
                'permissions' => $permissions,
            ]),
        ])->save();
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->invitation->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.invitation.update');
    }
}