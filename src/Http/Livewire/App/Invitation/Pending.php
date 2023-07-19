<?php

namespace Jiannius\Atom\Http\Livewire\App\Invitation;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Pending extends Component
{
    use WithPopupNotify;

    /**
     * Mount
     */
    public function mount(): void
    {
        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return 'Pending Invitations';
    }

    /**
     * Get invitations property
     */
    public function getInvitationsProperty(): mixed
    {
        return model('invitation')
            ->where('email', user('email'))
            ->status('pending')
            ->latest()
            ->get()
            ->map(fn($invitation) => [
                'id' => $invitation->id,
                'email' => $invitation->email,
                'created_by' => $invitation->createdBy->name,
                'date' => format_date($invitation->created_at),
                'tenant' => model('invitation')->usesHasTenant
                    ? $invitation->tenant->name
                    : null,
            ]);
    }

    /**
     * Accept
     */
    public function accept($id): mixed
    {
        $invitation = model('invitation')->find($id);
        $invitation->fill(['accepted_at' => now()])->save();

        if ($tenant = $invitation->tenant) {
            $tenant->setPreferred(user());
        }

        if ($permissions = data_get($invitation->data, 'permissions')) {
            foreach ($permissions as $permission) {
                model('permission')->create(array_merge(
                    [
                        'permission' => $permission,
                        'is_granted' => true,
                        'user_id' => user('id'),
                    ],

                    $invitation->tenant_id ? ['tenant_id' => $invitation->tenant_id] : [],
                ));
            }
        }

        return $this->reload();
    }

    /**
     * Decline
     */
    public function decline($id): mixed
    {
        $invitation = model('invitation')->find($id);
        $invitation->fill(['declined_at' => now()])->save();

        return $this->reload();
    }

    /**
     * Reload
     */
    public function reload(): mixed
    {
        return redirect()->route('app.invitation.pending');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.invitation.pending');
    }
}