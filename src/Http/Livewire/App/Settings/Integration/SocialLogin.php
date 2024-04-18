<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class SocialLogin extends Component
{
    use WithForm;
    
    public $settings;

    // validation
    public function validation() : array
    {
        return $this->providers->mapWithKeys(fn($val) => [
            'settings.'.get($val, 'name').'_client_id' => ['nullable'],
            'settings.'.get($val, 'name').'_client_secret' => ['nullable'],
        ])->toArray();
    }

    // mount
    public function mount() : void
    {
        $this->settings = $this->providers->mapWithKeys(fn($val) => [
            get($val, 'name').'_client_id' => settings(get($val, 'name').'_client_id'),
            get($val, 'name').'_client_secret' => settings(get($val, 'name').'_client_secret'),
        ])->toArray();
    }

    // get providers property
    public function getProvidersProperty() : mixed
    {
        return model('setting')->getSocialLogins(false);
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup('app.alert.updated');
    }
}