<?php

namespace Jiannius\Atom\Http\Livewire\App\User\Update;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Index extends Component
{
    use WithPopupNotify;
    
    public $user;

    /**
     * Mount
     */
    public function mount($userId): void
    {
        $this->user = model('user')->readable()->withTrashed()->findOrFail($userId);

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Resend activation email
     */
    public function resendActivationEmail(): void
    {
        $this->user->sendActivation();

        $this->popup('Activation email sent.', 'alert');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.user.update');
    }
}