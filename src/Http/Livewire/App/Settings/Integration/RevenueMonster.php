<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Illuminate\Support\Facades\Storage;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class RevenueMonster extends Component
{
    use WithForm;
    
    public $settings;

    // validation
    protected function validation() : array
    {
        return [
            'settings.revenue_monster_client_id' => ['required' => 'Client ID is required.'],
            'settings.revenue_monster_client_secret' => ['required' => 'Client secret is required.'],
            'settings.revenue_monster_store_id' => ['required' => 'Store ID is required.'],
            'settings.revenue_monster_private_key' => ['required' => 'Private key is required.'],
            'settings.revenue_monster_is_sandbox' => ['nullable'],
        ];
    }

    // mount
    public function mount() : void
    {
        $this->fill([
            'settings.revenue_monster_client_id' => settings('revenue_monster_client_id'),
            'settings.revenue_monster_client_secret' => settings('revenue_monster_client_secret'),
            'settings.revenue_monster_store_id' => settings('revenue_monster_store_id'),
            'settings.revenue_monster_private_key' => settings('revenue_monster_private_key'),
            'settings.revenue_monster_is_sandbox' => (bool) settings('revenue_monster_is_sandbox'),
        ]);
    }

    // test
    public function test() : void
    {
        $rm = app('revenue_monster');
        $profile = $rm->getMerchantProfile();

        if ($profile->id) {
            $this->popup([
                'title' => __('atom::revenue-monster.alert.test-success.title'),
                'message' => __('atom::revenue-monster.alert.test-success.message', ['company' => $profile->companyName]),
            ], 'alert', 'success');
        }
        else {
            $this->popup([
                'title' => __('atom::revenue-monster.alert.test-failed.title'),
                'message' => __('atom::revenue-monster.alert.test-failed.message'),
            ], 'alert', 'error');
        }
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        settings($this->settings);

        if ($key = settings('revenue_monster_private_key')) {
            $filename = 'rm-private-key.pem';
            Storage::delete($filename);
            Storage::append($filename, $key);
        }

        $this->popup('revenue-monster.alert.settings-updated');
    }
}