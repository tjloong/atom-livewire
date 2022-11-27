<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

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

        if (!in_array($this->tab, [
            'login', 
            'account/login',
            'password',
            'account/password',
        ])) {
            $this->authorize('setting.manage');
        }

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
        $authorize = auth()->user()->can('setting.manage');

        $account = ['group' => 'Account', 'tabs' => [
            ['slug' => 'account/login', 'label' => 'Login Information', 'icon' => 'arrow-right-to-bracket'],
            ['slug' => 'account/password', 'label' => 'Change Password', 'icon' => 'lock'],
            
            enabled_module('plans') 
            && auth()->user()->isAccountType('signup')
            && $authorize
                ? ['label' => 'Billing', 'href' => route('app.billing.home'), 'icon' => 'file-invoice-dollar']
                : null,
        ]];

        $system = $authorize ? ['group' => 'System', 'tabs' => [
            ['slug' => 'system/user', 'label' => 'Users', 'icon' => 'users'],

            enabled_module('roles')
                ? ['slug' => 'system/role', 'label' => 'Roles', 'icon' => 'user-tag']
                : null,
            
            enabled_module('teams')
                ? ['slug' => 'system/team', 'label' => 'Teams', 'icon' => 'people-group']
                : null,

            enabled_module('plans') 
                ? ['slug' => 'plans', 'label' => 'Plans', 'icon' => 'cube'] 
                : null,

            ['slug' => 'pages', 'label' => 'Pages', 'icon' => 'file'],
            ['slug' => 'system/file', 'label' => 'Files and Media', 'icon' => 'folder'],
        ]] : null;

        $website = $authorize ? ['group' => 'Website', 'tabs' => [
            ['slug' => 'website/profile', 'label' => 'Profile', 'icon' => 'globe'],
            ['slug' => 'website/seo', 'label' => 'SEO', 'icon' => 'search'],
            ['slug' => 'website/analytics', 'label' => 'Analytics', 'icon' => 'chart-simple'],
            ['slug' => 'website/social-media', 'label' => 'Social Media', 'icon' => 'share-nodes'],
            ['slug' => 'website/announcement', 'label' => 'Announcement', 'icon' => 'bullhorn'],
        ]] : null;

        $integration = $authorize ? ['group' => 'Integration', 'tabs' => [
            ['slug' => 'integration/email', 'label' => 'Email', 'icon' => 'paper-plane'],
            ['slug' => 'integration/storage', 'label' => 'Storage', 'icon' => 'hard-drive'],
            ['slug' => 'integration/payment', 'label' => 'Payment', 'icon' => 'money-bill'],
        ]] : null;

        return array_filter([$account, $system, $website, $integration]);
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
                    'labels' => 'app.label.listing',
                    'pages' => 'app.page.listing',
                    'plans' => 'app.plan.listing',
                ][$this->tab] 
                ?? 'app.settings.'.$this->tab
            ),
        ]);
    }
}