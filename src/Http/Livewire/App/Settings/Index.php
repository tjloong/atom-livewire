<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    // mount
    public function mount()
    {
        if ($this->tab) {
            $tab = tabs($this->filteredTabs, $this->tab);
            if (!$tab || data_get($tab, 'enabled') === false) abort(404);

            $this->tab = data_get($tab, 'slug');
        }
        elseif ($first = data_get(tabs($this->filteredTabs)->first(), 'slug')) {
            return redirect()->route('app.settings', [$first]);
        }
    }

    // get title propert
    public function getTitleProperty(): string
    {
        return 'Settings';
    }

    // get filtered tabs property
    public function getFilteredTabsProperty(): array
    {
        return collect($this->tabs)
            ->filter(fn($tab) => data_get($tab, 'enabled') !== false)
            ->filter(fn($tab) => data_get($tab, 'hidden') !== true)
            ->values()
            ->map(fn($tab) => ($children = data_get($tab, 'tabs'))
                ? array_merge($tab, [
                    'tabs' => collect($children)
                        ->filter(fn($tab) => data_get($tab, 'enabled') !== false)
                        ->filter(fn($tab) => data_get($tab, 'hidden') !== true)
                        ->values()
                        ->all(),
                ])
                : $tab
            )
            ->all();
    }

    // get tabs property
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
            ], 'enabled' => enabled_module('plans')],

            ['group' => 'System', 'tabs' => [
                [
                    'slug' => 'user', 
                    'label' => 'Users', 
                    'icon' => 'users', 
                    'livewire' => 'app.user.listing'
                ],
                [
                    'slug' => 'invitation',
                    'label' => 'Invitations',
                    'icon' => 'envelope-circle-check',
                    'livewire' => 'app.invitation.listing',
                    'enabled' => enabled_module('invitations'),
                ],
                [
                    'slug' => 'role', 
                    'label' => 'Roles', 
                    'icon' => 'user-tag', 
                    'livewire' => 'app.role.listing',
                    'enabled' => enabled_module('roles'),
                ],
                [
                    'slug' => 'team', 
                    'label' => 'Teams', 
                    'icon' => 'people-group', 
                    'livewire' => 'app.team.listing',
                    'enabled' => enabled_module('teams'),
                ],
                [
                    'slug' => 'pages', 
                    'label' => 'Pages', 
                    'icon' => 'file', 
                    'livewire' => 'app.page.listing',
                    'enabled' => enabled_module('pages'),
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
                    'enabled' => count(config('atom.payment_gateway')) > 0,
                ],
                [
                    'slug' => 'integration/social-login',
                    'label' => 'Social Login',
                    'icon' => 'login',
                    'enabled' => count(config('atom.auth.login')) > 0,
                ],
            ]]
        ];
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.settings');
    }
}