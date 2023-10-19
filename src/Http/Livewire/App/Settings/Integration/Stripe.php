<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Stripe extends Component
{
    use WithForm;

    public $settings;

    // validation
    protected function validation() : array
    {
        return [
            'settings.stripe_public_key' => ['required' => 'Public key is required.'],
            'settings.stripe_secret_key' => ['required' => 'Secret key is required.'],
            'settings.stripe_webhook_signing_secret' => ['nullable'],
        ];
    }

    // mount
    public function mount() : void
    {
        foreach ([
            'stripe_public_key',
            'stripe_secret_key',
            'stripe_webhook_signing_secret',
        ] as $key) {
            $this->fill(['settings.'.$key => settings($key)]);
        }
    }

    public function submit()
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup(__('atom::stripe.alert.updated'));
    }
}