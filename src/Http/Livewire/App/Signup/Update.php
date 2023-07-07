<?php

namespace Jiannius\Atom\Http\Livewire\App\Signup;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Livewire\Component;

class Update extends Component
{
    use WithBreadcrumbs;

    public $tab;
    public $user;

    // mount
    public function mount($id = null)
    {
        $this->user = tier('root')
            ? model('user')->findOrFail($id)
            : user();

        if (!$this->tab) {
            return to_route('app.signup.update', [
                $this->user->id,
                'tab' => data_get(tabs($this->tabs), '0.slug'),
            ]);
        }
    }

    // get tabs property
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'Account', 'tabs' => [
                ['slug' => 'info', 'label' => 'Sign-Up Information', 'livewire' => 'app.signup.info'],
            ]],
        ];
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.signup.update');
    }
}