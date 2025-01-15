<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
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
            'settings.finexus_url' => ['required' => 'URL is required.'],
            'settings.finexus_query_url' => ['required' => 'Query URL is required.'],
        ];
    }

    // mount
    public function mount() : void
    {
        $this->fill([
            'settings.finexus_merchant_id' => settings('finexus_merchant_id'),
            'settings.finexus_secret_key' => settings('finexus_secret_key'),
            'settings.finexus_terminal_id' => settings('finexus_terminal_id'),
            'settings.finexus_url' => settings('finexus_url'),
            'settings.finexus_query_url' => settings('finexus_query_url'),
        ]);
    }

    // test
    public function test() : void
    {
        if (app('finexus')->test()) $this->popup('app.alert.connection-ok', 'alert', 'success');
        else $this->popup('app.alert.connection-failed', 'alert', 'error');
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup('app.alert.updated');
    }
}