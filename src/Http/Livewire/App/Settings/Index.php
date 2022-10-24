<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    /**
     * Mount
     */
    public function mount($tab = null)
    {
        if (!$tab || !$this->getFlatTabs()->firstWhere('slug', $tab)) {
            return redirect()->route('app.settings', [
                data_get($this->getFlatTabs()->first(), 'slug')
            ]);
        }

        $this->tab = $tab;

        breadcrumbs()->home($this->title);
        breadcrumbs()->push(data_get($this->getFlatTabs()->firstWhere('slug', $tab), 'label'));
    }

    /**
     * Get title propert
     */
    public function getTitleProperty()
    {
        return 'Settings';
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        return [
            ['group' => 'Account', 'tabs' => [
                ['slug' => 'account/login', 'label' => 'Login Information', 'icon' => 'arrow-right-to-bracket'],
                ['slug' => 'account/password', 'label' => 'Change Password', 'icon' => 'lock'],
                
                enabled_module('plans') && auth()->user()->isAccountType('signup')
                    ? ['label' => 'Billing', 'href' => route('app.billing.home'), 'icon' => 'file-invoice-dollar']
                    : null,
            ]],
            
            ['group' => 'System', 'tabs' => [
                ['slug' => 'users', 'label' => 'Users', 'icon' => 'users'],

                enabled_module('roles')
                    ? ['slug' => 'roles', 'label' => 'Roles', 'icon' => 'user-tag']
                    : null,
                
                enabled_module('teams')
                    ? ['slug' => 'teams', 'label' => 'Teams', 'icon' => 'people-group']
                    : null,

                enabled_module('plans') 
                    ? ['slug' => 'plans', 'label' => 'Plans', 'icon' => 'cube'] 
                    : null,

                ['slug' => 'pages', 'label' => 'Pages', 'icon' => 'file'],
                ['slug' => 'files', 'label' => 'Files and Media', 'href' => route('app.files'), 'icon' => 'folder'],
            ]],

            ['group' => 'Website', 'tabs' => [
                ['slug' => 'website/profile', 'label' => 'Profile', 'icon' => 'globe'],
                ['slug' => 'website/seo', 'label' => 'SEO', 'icon' => 'search'],
                ['slug' => 'website/analytics', 'label' => 'Analytics', 'icon' => 'chart-simple'],
                ['slug' => 'website/social-media', 'label' => 'Social Media', 'icon' => 'share-nodes'],
                ['slug' => 'website/announcement', 'label' => 'Announcement', 'icon' => 'bullhorn'],
            ]],
            
            ['group' => 'Integration', 'tabs' => [
                ['slug' => 'integration/email', 'label' => 'Email', 'icon' => 'paper-plane'],
                ['slug' => 'integration/storage', 'label' => 'Storage', 'icon' => 'hard-drive'],
                ['slug' => 'integration/payment', 'label' => 'Payment', 'icon' => 'money-bill'],
            ]],
        ];
    }

    /**
     * Get flat tabs
     */
    public function getFlatTabs()
    {
        return collect($this->tabs)->pluck('tabs')->collapse()->filter();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings', [
            'livewire' => lw(
                data_get($this->getFlatTabs()->firstWhere('slug', $this->tab), 'livewire')
                ?? [
                    'users' => 'app.user.listing',
                    'labels' => 'app.label.listing',
                    'pages' => 'app.page.listing',
                    'plans' => 'app.plan.listing',
                    'roles' => 'app.role.listing',
                    'teams' => 'app.team.listing',
                    'taxes' => 'app.tax.listing',
                ][$this->tab] 
                ?? 'app.settings.'.$this->tab
            ),
        ]);
    }
}