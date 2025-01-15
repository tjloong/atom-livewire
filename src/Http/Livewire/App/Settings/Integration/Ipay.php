<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Livewire\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Ipay extends Component
{
    use WithForm;

    public $settings;

    // validations
    protected function validation() : array
    {
        return [
            'settings.ipay_merchant_code' => ['required' => 'Merchant code is required.'],
            'settings.ipay_merchant_key' => ['required' => 'Merchant key is required.'],
            'settings.ipay_url' => ['required' => 'URL is required.'],
            'settings.ipay_query_url' => ['required' => 'Re-query URL is required.'],
        ];
    }

    // mount
    public function mount() : void
    {
        $this->settings = [
            'ipay_merchant_code' => settings('ipay_merchant_code'),
            'ipay_merchant_key' => settings('ipay_merchant_key'),
            'ipay_url' => settings('ipay_url'),
            'ipay_query_url' => settings('ipay_query_url'),
        ];
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup('common.alert.updated');
    }
}