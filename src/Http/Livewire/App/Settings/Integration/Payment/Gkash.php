<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration\Payment;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Gkash extends Component
{
    use WithPopupNotify;

    public $settings;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'settings.gkash_mid' => 'required',
            'settings.gkash_signature_key' => 'required',
            'settings.gkash_url' => 'required',
            'settings.gkash_sandbox_url' => 'nullable',
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
            'settings.gkash_url.required' => __('URL is required.'),
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
        site_settings($this->settings);
        $this->popup('Gkash Information Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.integration.payment.gkash');
    }
}