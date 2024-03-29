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
    }

    // get tabs property
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'app.label.account', 'tabs' => [
                ['slug' => 'login', 'label' => 'app.label.login-information', 'icon' => 'login',],
                ['slug' => 'password', 'label' => 'app.label.change-password', 'icon' => 'lock',],
            ]],

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
}