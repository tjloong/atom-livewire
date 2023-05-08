<?php

namespace Jiannius\Atom\Http\Livewire\App\Invitation;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Create extends Component
{
    use WithPopupNotify;

    public $invitation;

    public $inputs = [
        'search' => null,
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->invitation = model('invitation');

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return 'Invite User';
    }

    /**
     * Get header property
     */
    public function getHeaderProperty(): string
    {
        if (tenant()) return 'Invite user to join '.tenant('name');
        else return 'Invite user to join '.config('app.name');
    }

    /**
     * Get users property
     */
    public function getUsersProperty(): mixed
    {
        if ($search = data_get($this->inputs, 'search')) {
            $users = model('user')
                ->when(tenant(), fn($q) => $q->tier('tenant'))
                ->where(fn($q) => $q
                    ->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "$search%")
                )
                ->orderBy('email')
                ->take(50)
                ->get();

            if ($users->count()) return $users;
            else if ($this->isEmail) return collect([['email' => $search]]);
        }

        return null;
    }

    /**
     * Get is email property
     */
    public function getIsEmailProperty(): bool
    {
        if ($search = data_get($this->inputs, 'search')) {
            $validator = validator(
                ['search' => $search],
                ['search' => 'email'],
            );
    
            return !$validator->fails();
        }

        return false;
    }

    /**
     * Invite
     */
    public function invite($email)
    {
        $isPending = model('invitation')
            ->status('pending')
            ->where('email', $email)
            ->when(
                tenant() && model('invitation')->enabledHasTenantTrait,
                fn($q) => $q->where('tenant_id', tenant('id')),
            )
            ->count() > 0;

        if ($isPending) {
            $this->popup([
                'title' => 'Unable To Invite User',
                'message' => 'User has been invited before.',
            ], 'alert', 'error');

            $this->reset('inputs');
        }
        else {
            $invitation = model('invitation')->create(['email' => $email]);

            return redirect()->route('app.invitation.update', [$invitation->id]);
        }

    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.invitation.create');
    }
}