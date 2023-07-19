<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    // mount
    public function mount()
    {
        $this->tab = $this->tab ?? data_get(tabs($this->tabs), '0.slug');

        if (!$this->livewire) abort(404);
    }

    // get tabs property
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'Account', 'tabs' => [
                ['slug' => 'login', 'label' => 'Login Information', 'icon' => 'login',],
                ['slug' => 'password', 'label' => 'Change Password', 'icon' => 'lock',],
                ['slug' => 'billing', 'label' => 'Subscription', 'icon' => 'credit-card'],
            ]],

            ['group' => 'System', 'tabs' => [
                ['slug' => 'user', 'label' => 'Users', 'icon' => 'users'],
                ['slug' => 'invitation','label' => 'Invitations', 'icon' => 'invitation'],
                ['slug' => 'role', 'label' => 'Roles', 'icon' => 'user-tag'],
                ['slug' => 'team', 'label' => 'Teams', 'icon' => 'people-group'],
                ['slug' => 'pages', 'label' => 'Pages', 'icon' => 'newspaper'],
                ['slug' => 'file', 'label' => 'Files and Media', 'icon' => 'images'],
            ]],

            ['group' => 'Website', 'tabs' => [
                ['slug' => 'website/profile', 'label' => 'Profile', 'icon' => 'globe'],
                ['slug' => 'website/seo', 'label' => 'SEO', 'icon' => 'search'],
                ['slug' => 'website/analytics', 'label' => 'Analytics', 'icon' => 'chart-simple'],
                ['slug' => 'website/social-media', 'label' => 'Social Media', 'icon' => 'share-nodes'],
                ['slug' => 'website/announcement', 'label' => 'Announcement', 'icon' => 'bullhorn'],
                ['slug' => 'website/popup', 'label' => 'Pop-Up', 'icon' => 'window-restore'],
            ]],

            ['group' => 'Integration', 'tabs' => [
                ['slug' => 'integration/email', 'label' => 'Email', 'icon' => 'paper-plane'],
                ['slug' => 'integration/storage', 'label' => 'Storage', 'icon' => 'hard-drive'],
                ['slug' => 'integration/payment', 'label' => 'Payment', 'icon' => 'money-bill'],
                ['slug' => 'integration/social-login', 'label' => 'Social Login', 'icon' => 'login'],
            ]]
        ];
    }

    // get livewire property
    public function getLivewireProperty(): mixed
    {
        if (tabs($this->tabs, $this->tab)) {
            return atom_lw('app.settings.'.$this->tab);
        }
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.settings');
    }
}