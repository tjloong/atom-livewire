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

    // test
    public function test() : bool
    {
        $test = app('stripe', [
            'public_key' => get($this->settings, 'stripe_public_key'),
            'secret_key' => get($this->settings, 'stripe_secret_key'),
        ])->test();

        if ($e = get($test, 'error')) $this->popup($e, 'alert', 'error');
        else $this->popup(tr('app.label.connection-ok'), 'alert', 'success');

        return get($test, 'success');
    }

    public function submit()
    {
        $this->validateForm();

        if ($this->test()) {
            $this->settings = [
                ...$this->settings,
                'stripe_webhook_signing_secret' => app('stripe', [
                    'public_key' => get($this->settings, 'stripe_public_key'),
                    'secret_key' => get($this->settings, 'stripe_secret_key'),
                ])->createWebhook()
            ];

            settings($this->settings);

            $this->popup('app.alert.updated');
        }
    }
}