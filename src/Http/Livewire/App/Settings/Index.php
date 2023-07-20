<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $tab;

    // mount
    public function mount()
    {
        parent::mount();

        $this->tab = $this->tab ?? data_get(tabs($this->tabs), '0.slug');

        if (!$this->component) abort(404);
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

    // get component property
    public function getComponentProperty(): mixed
    {
        if ($tab = tabs($this->tabs, $this->tab)) {
            $slug = data_get($tab, 'slug');

            if (has_component('app.settings.'.$slug)) {
                $name = str('app.settings.'.$slug)->replace('/', '.')->toString();
                $params = null;
            }
            else {
                $split = collect(explode('/', $slug));
                $name = 'app.settings.'.$split->shift();
                $params = $split->toArray();
            }

            return compact('name', 'params');
        }

        return null;
    }
}