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

        if (!$this->tab) {
            return redirect()->route('app.signup.update', [
                $this->user->id,
                'tab' => data_get(tabs($this->tabs), '0.slug'),
            ]);
        }

        breadcrumbs()->push($this->user->name);
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'Account', 'tabs' => [
                ['slug' => 'info', 'label' => 'Sign-Up Information', 'livewire' => 'app.signup.info'],
            ]],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.signup.update');
    }
}