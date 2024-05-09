<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $tab;

    // mount
    public function mount()
    {
        $this->tab = $this->tab ?? data_get(tabs($this->tabs), '0.slug');

        if (!tabs($this->tabs, $this->tab)) abort(404);
    }

    // get tabs property
    public function getTabsProperty(): array
    {
        return [
            ['slug' => 'profile', 'label' => 'app.label.my-profile', 'icon' => 'id-card-clip',],

            ['group' => 'app.label.system', 'tabs' => [
                ['slug' => 'site', 'label' => 'app.label.site', 'icon' => 'globe'],
                ['slug' => 'user', 'label' => 'app.label.user:2', 'icon' => 'users'],
                ['slug' => 'file', 'label' => 'app.label.file:2', 'icon' => 'images'],
            ]],

            ['group' => 'app.label.integration', 'tabs' => [
                ['slug' => 'integration/email', 'label' => 'app.label.email', 'icon' => 'paper-plane'],
                ['slug' => 'integration/storage', 'label' => 'app.label.storage', 'icon' => 'hard-drive'],
                ['slug' => 'integration/payment', 'label' => 'app.label.payment', 'icon' => 'money-bill'],
                ['slug' => 'integration/social-login', 'label' => 'app.label.social-login', 'icon' => 'login'],
            ]]
        ];
    }

    // get livewire property
    public function getLivewireProperty() : array
    {
        $name = (string) str('app/settings/'.$this->tab)->replace('/', '.');
        $params = [];

        if (!find_livewire($name)) {
            $split = collect(explode('/', $this->tab));
            $name = 'app.settings.'.$split->shift();
            $params = ['slug' => $split->join('/')];
        }

        return compact('name', 'params');
    }
}