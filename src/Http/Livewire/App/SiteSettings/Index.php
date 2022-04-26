<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Index extends Component
{
    public $tab;

    protected $listeners = ['submit'];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->tab) {
            $group = $this->tabs->first();
            $tab = data_get($group, 'tabs')->first();
            $this->tab = data_get($tab, 'slug');
        }

        breadcrumbs()->flush();
    }

    /**
     * Get tabs property
     */
    public function getTabsProperty()
    {
        $settings = model('site_setting');

        return collect([
            ['group' => 'general', 'tabs' => collect([
                $settings->group('profile')->count() ? ['slug' => 'profile', 'label' => 'Site Profile'] : null,
                $settings->group('seo')->count() ? ['slug' => 'seo', 'label' => 'Site SEO'] : null,
                $settings->group('analytics')->count() ? ['slug' => 'analytics'] : null,
                $settings->group('social')->count() ? ['slug' => 'social-media'] : null,
                $settings->group('whatsapp')->count() ? ['slug' => 'whatsapp-bubble'] : null,
            ])->filter()],

            ['group' => 'system', 'tabs' => collect([
                ['slug' => 'system/email', 'label' => 'Email Configuration'],
                ['slug' => 'system/storage', 'label' => 'Storage'],
            ])->filter()],
                
            ['group' => 'payment-gateway', 'tabs' => collect([
                in_array('stripe', config('atom.payment_gateway', [])) 
                    ? ['slug' => 'payment-gateway/stripe', 'label' => 'Stripe'] : null,
                in_array('gkash', config('atom.payment_gateway', []))
                    ? ['slug' => 'payment-gateway/gkash', 'label' => 'GKash'] : null,
                in_array('ozopay', config('atom.payment_gateway', [])) 
                    ? ['slug' => 'payment-gateway/ozopay', 'label' => 'Ozopay'] : null,
                in_array('ipay', config('atom.payment_gateway', []))
                    ? ['slug' => 'payment-gateway/ipay', 'label' => 'iPay88'] : null,
            ])->filter()],
        ])->filter(fn($tab) => $tab['tabs']->count() > 0);
    }

    /**
     * Submit
     */
    public function submit($settings)
    {
        site_settings($settings);

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.index');
    }
}