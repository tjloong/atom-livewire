<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Finexus extends Component
{
    use WithForm;
    
    public $settings;

    // validation
    protected function validation() : array
    {
        return [
            'settings.finexus_merchant_id' => ['required' => 'Merchant ID is required.'],
            'settings.finexus_secret_key' => ['required' => 'Secret key is required.'],
            'settings.finexus_terminal_id' => ['nullable'],
        ];
    }

    // mount
    public function mount() : void
    {
        $this->fill([
            'settings.finexus_merchant_id' => settings('finexus_merchant_id'),
            'settings.finexus_secret_key' => settings('finexus_secret_key'),
            'settings.finexus_terminal_id' => settings('finexus_terminal_id'),
        ]);
    }

    // test
    public function test() : mixed
    {
        return app('finexus')->checkout([
            'PaymentID' => 'U01',
            'MerchRefNo' => 'merchant-refNo-0001',
            'CurrCode' => '458',
            'TxnAmt' => '1.00',
            'ExpTxnAmt' => '2',
            'EcommMerchInd' => '1',
            'CountryCode' => 'MY',
            'TokenFlag' => 'N',
            'PreAuthFlag' => 'N',
        ]);
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup('app.alert.updated');
    }
}