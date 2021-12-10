<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use Livewire\Component;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Validator;

class Email extends Component
{
    public $settings;

    protected $rules = [
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

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        SiteSetting::email()->get()->each(function($setting) {
            $this->settings[$setting->name] = $setting->value;
        });
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.site-settings.form.email');
    }

    /**
     * Save settings
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();

        foreach ($this->settings as $key => $value) {
            SiteSetting::where('name', $key)->update(['value' => $value]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => 'Site Settings Updated', 'type' => 'success']);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateinputs()
    {
        $this->resetValidation();

        $validator = Validator::make(
            ['settings' => $this->settings],
            $this->rules,
            [
                'settings.notify_to.required' => 'Notification to email address is required.',
                'settings.notify_to.email' => 'Invalid notification to email address.',
                'settings.mailer.required' => 'Email provider is required.',
                'settings.smtp_host.required_if' => 'SMTP host is required.',
                'settings.smtp_port.required_if' => 'SMTP port number is required.',
                'settings.smtp_username.required_if' => 'SMTP username is required.',
                'settings.smtp_password.required_if' => 'SMTP password is required.',
                'settings.mailgun_domain.required_if' => 'Mailgun domain is required.',
                'settings.mailgun_secret.required_if' => 'Mailgun secret is required.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}