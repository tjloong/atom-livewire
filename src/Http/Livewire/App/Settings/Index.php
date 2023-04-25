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
    public function mount()
    {
        if ($this->tab) {
            $tab = tabs($this->tabs, $this->tab);
            if (!$tab || data_get($tab, 'disabled')) abort(404);
    
            $this->tab = data_get($tab, 'slug');
    
            breadcrumbs()->home($this->title);
        }
        else {
            return redirect()->route('app.settings', [data_get(tabs($this->filteredTabs)->first(), 'slug')]);
        }
    }

    /**
     * Get title propert
     */
    public function getTitleProperty(): string
    {
        return 'Settings';
    }

    /**
     * Get filtered tabs property
     */
    public function getFilteredTabsProperty()
    {
        return collect($this->tabs)
            ->filter(fn($tab) => data_get($tab, 'disabled') !== true)
            ->filter(fn($tab) => data_get($tab, 'hidden') !== true)
            ->values()
            ->map(fn($tab) => ($children = data_get($tab, 'tabs'))
                ? array_merge($tab, [
                    'tabs' => collect($children)
                        ->filter(fn($tab) => data_get($tab, 'disabled') !== true)
                        ->filter(fn($tab) => data_get($tab, 'hidden') !== true)
                        ->values()
                        ->all(),
                ])
                : $tab
            )
            ->all();
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty(): array
    {
        return [
            ['group' => 'Account', 'tabs' => [
                [
                    'slug' => 'login', 
                    'label' => 'Login Information', 
                    'icon' => 'arrow-right-to-bracket',
                ],
                [
                    'slug' => 'password', 
                    'label' => 'Change Password', 
                    'icon' => 'lock',
                ],
            ]],

            ['group' => 'Billing', 'tabs' => [
                [
                    'slug' => 'billing',
                    'label' => 'Subscription',
                    'icon' => 'credit-card',
                    'livewire' => 'app.billing',
                ],
                [
                    'slug' => 'billing/checkout',
                    'label' => 'Checkout',
                    'livewire' => 'app.billing.checkout',
                    'hidden' => true,
                ],
            ]],

            ['group' => 'System', 'tabs' => [
                [
                    'slug' => 'user', 
                    'label' => 'Users', 
                    'icon' => 'users', 
                    'livewire' => 'app.user.listing'
                ],
                [
                    'slug' => 'role', 
                    'label' => 'Roles', 
                    'icon' => 'user-tag', 
                    'livewire' => 'app.role.listing',
                    'disabled' => !enabled_module('roles'),
                ],
                [
                    'slug' => 'team', 
                    'label' => 'Teams', 
                    'icon' => 'people-group', 
                    'livewire' => 'app.team.listing',
                    'disabled' => !enabled_module('teams'),
                ],
                [
                    'slug' => 'pages', 
                    'label' => 'Pages', 
                    'icon' => 'file', 
                    'livewire' => 'app.page.listing',
                    'disabled' => !enabled_module('pages'),
                ],
                [
                    'slug' => 'file', 
                    'label' => 'Files and Media', 
                    'icon' => 'folder', 
                    'livewire' => 'app.file.listing',
                ],
            ]],

            ['group' => 'Website', 'tabs' => [
                [
                    'slug' => 'website/profile',
                    'label' => 'Profile',
                    'icon' => 'globe',
                ],
                [
                    'slug' => 'website/seo',
                    'label' => 'SEO',
                    'icon' => 'search',
                ],
                [
                    'slug' => 'website/analytics',
                    'label' => 'Analytics',
                    'icon' => 'chart-simple',
                ],
                [
                    'slug' => 'website/social-media',
                    'label' => 'Social Media',
                    'icon' => 'share-nodes',
                ],
                [
                    'slug' => 'website/announcement',
                    'label' => 'Announcement',
                    'icon' => 'bullhorn',
                ],
                [
                    'slug' => 'website/popup',
                    'label' => 'Pop-Up',
                    'icon' => 'window-restore',
                ],
            ]],
            ['group' => 'Integration', 'tabs' => [
                [
                    'slug' => 'integration/email',
                    'label' => 'Email',
                    'icon' => 'paper-plane',
                ],
                [
                    'slug' => 'integration/storage',
                    'label' => 'Storage',
                    'icon' => 'hard-drive',
                ],
                [
                    'slug' => 'integration/payment',
                    'label' => 'Payment',
                    'icon' => 'money-bill',
                    'disabled' => !count(config('atom.payment_gateway')),
                ],
                [
                    'slug' => 'integration/social-login',
                    'label' => 'Social Login',
                    'icon' => 'login',
                    'disabled' => !count(config('atom.auth.login')),
                ],
            ]]
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings');
    }
}