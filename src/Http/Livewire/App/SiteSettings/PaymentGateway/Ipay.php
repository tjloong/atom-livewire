<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\PaymentGateway;

use Livewire\Component;

class Ipay extends Component
{
    public $settings;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'settings.ipay_merchant_code' => 'required',
            'settings.ipay_merchant_key' => 'required',
            'settings.ipay_url' => 'required',
            'settings.ipay_query_url' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'settings.ipay_merchant_code.required' => __('Merchant code is required.'),
            'settings.ipay_merchantkey.required' => __('Merchant key is required.'),
            'settings.ipay_url.required' => __('URL is required.'),
            'settings.ipay_query_url.required' => __('Re-query URL is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->group('ipay')->get()->each(function($setting) {
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
        return view('atom::app.site-settings.payment-gateway.ipay');
    }
}