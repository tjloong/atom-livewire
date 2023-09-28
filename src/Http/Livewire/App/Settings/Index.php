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
            ['group' => 'atom::settings.sidenav.group.account', 'tabs' => [
                ['slug' => 'login', 'label' => 'Login Information', 'icon' => 'login',],
                ['slug' => 'password', 'label' => 'Change Password', 'icon' => 'lock',],
            ]],

            ['group' => 'atom::settings.sidenav.group.system', 'tabs' => [
                ['slug' => 'user', 'label' => 'atom::settings.sidenav.tab.user', 'icon' => 'users'],
                ['slug' => 'role', 'label' => 'atom::settings.sidenav.tab.role', 'icon' => 'user-tag'],
                ['slug' => 'page', 'label' => 'atom::settings.sidenav.tab.page', 'icon' => 'newspaper'],
                ['slug' => 'file', 'label' => 'atom::settings.sidenav.tab.file', 'icon' => 'images'],
            ]],

            ['group' => 'atom::settings.sidenav.group.labels', 'tabs' => [
                ['slug' => 'label/blog-category', 'label' => 'atom::settings.sidenav.tab.blog', 'icon' => 'tag'],
            ]],

            ['group' => 'atom::settings.sidenav.group.website', 'tabs' => [
                ['slug' => 'website/profile', 'label' => 'atom::settings.sidenav.tab.profile', 'icon' => 'globe'],
                ['slug' => 'website/seo', 'label' => 'atom::settings.sidenav.tab.seo', 'icon' => 'search'],
                ['slug' => 'website/analytics', 'label' => 'atom::settings.sidenav.tab.analytics', 'icon' => 'chart-simple'],
                ['slug' => 'website/social-media', 'label' => 'atom::settings.sidenav.tab.social-media', 'icon' => 'share-nodes'],
                ['slug' => 'website/announcement', 'label' => 'atom::settings.sidenav.tab.announcement', 'icon' => 'bullhorn'],
                ['slug' => 'website/popup', 'label' => 'atom::settings.sidenav.tab.popup', 'icon' => 'window-restore'],
            ]],

            ['group' => 'atom::settings.sidenav.group.integration', 'tabs' => [
                ['slug' => 'integration/email', 'label' => 'atom::settings.sidenav.tab.email', 'icon' => 'paper-plane'],
                ['slug' => 'integration/storage', 'label' => 'atom::settings.sidenav.tab.storage', 'icon' => 'hard-drive'],
                ['slug' => 'integration/payment', 'label' => 'atom::settings.sidenav.tab.payment', 'icon' => 'money-bill'],
                ['slug' => 'integration/social-login', 'label' => 'atom::settings.sidenav.tab.social-login', 'icon' => 'login'],
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