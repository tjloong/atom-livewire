<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Traits\WithPopupNotify;
use Livewire\Component;

class Email extends Component
{
    use WithPopupNotify;
    
    public $settings;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'settings.notify_from' => 'required|email',
            'settings.notify_to' => 'required|email',
            'settings.mailer' => 'required',
            'settings.smtp_host' => 'required_if:settings.mailer,smtp',
            'settings.smtp_port' => 'required_if:settings.mailer,smtp',
            'settings.smtp_username' => 'required_if:settings.mailer,smtp',
            'settings.smtp_password' => 'required_if:settings.mailer,smtp',
            'settings.smtp_encryption' => 'nullable',
            'settings.mailgun_domain' => 'required_if:settings.mailer,mailgun',
            'settings.mailgun_secret' => 'required_if:settings.mailer,mailgun',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'settings.notify_to.required' => __('Notification to email address is required.'),
            'settings.notify_to.email' => __('Invalid notification to email address.'),
            'settings.mailer.required' => __('Email provider is required.'),
            'settings.smtp_host.required_if' => __('SMTP host is required.'),
            'settings.smtp_port.required_if' => __('SMTP port number is required.'),
            'settings.smtp_username.required_if' => __('SMTP username is required.'),
            'settings.smtp_password.required_if' => __('SMTP password is required.'),
            'settings.mailgun_domain.required_if' => __('Mailgun domain is required.'),
            'settings.mailgun_secret.required_if' => __('Mailgun secret is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        model('site_setting')->group('email')->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        site_settings($this->settings);
        
        $this->popup('Email Integration Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.settings.integration.email');
    }
}