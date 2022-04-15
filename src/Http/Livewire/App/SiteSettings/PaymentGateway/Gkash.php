<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\PaymentGateway;

use Livewire\Component;

class Gkash extends Component
{
    public $settings;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'settings.gkash_mid' => 'required',
            'settings.gkash_signature_key' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'settings.gkash_mid.required' => __('Merchant ID is required.'),
            'settings.gkash_signature_key.required' => __('Signature key is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->group('gkash')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->emitUp('submit', $this->settings);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.payment-gateway.gkash');
    }
}