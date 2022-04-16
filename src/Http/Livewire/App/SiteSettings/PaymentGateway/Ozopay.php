<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings\PaymentGateway;

use Livewire\Component;

class Ozopay extends Component
{
    public $settings;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'settings.ozopay_tid' => 'required',
            'settings.ozopay_secret' => 'required',
            'settings.ozopay_url' => 'required',
            'settings.ozopay_sandbox_url' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'settings.ozopay_tid.required' => __('Terminal ID is required.'),
            'settings.ozopay_secret.required' => __('Secret is required.'),
            'settings.ozopay_url.required' => __('URL is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->group('ozopay')->get()->each(function($setting) {
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
        return view('atom::app.site-settings.payment-gateway.ozopay');
    }
}