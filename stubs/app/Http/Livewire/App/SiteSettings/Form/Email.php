<?php

namespace App\Http\Livewire\App\SiteSettings\Form;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Email extends Component
{
    public $settings;

    protected $rules = [
        'settings.smtp_host' => 'required',
        'settings.smtp_port' => 'required',
        'settings.smtp_username' => 'required',
        'settings.smtp_password' => 'required',
        'settings.smtp_encryption' => 'nullable',
        'settings.notify_from' => 'required|email',
        'settings.notify_to' => 'required|email',
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
                'settings.smtp_host.required' => 'SMTP host is required.',
                'settings.smtp_port.required' => 'SMTP port number is required.',
                'settings.smtp_username.required' => 'SMTP username is required.',
                'settings.smtp_password.required' => 'SMTP password is required.',
                'settings.smtp_encryption.required' => 'SMTP encryption is required.',
                'settings.notify_from.required' => 'Notification from email address is required.',
                'settings.notify_from.email' => 'Invalid notification from email address.',
                'settings.notify_to.required' => 'Notification to email address is required.',
                'settings.notify_to.email' => 'Invalid notification to email address.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}