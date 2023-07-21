<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class SocialLogin extends Component
{
    use WithForm;
    use WithPopupNotify;
    
    public $settings;

    // validation
    public function validation(): array
    {
        return collect($this->platforms)->mapWithKeys(fn($platform) => [
            'settings.'.$platform.'_client_id' => ['nullable'],
            'settings.'.$platform.'_client_secret' => ['nullable'],
        ])->toArray();
    }

    // mount
    public function mount(): void
    {
        parent::mount();

        $this->settings = collect($this->platforms)
            ->mapWithKeys(fn($platform) => [
                $platform.'_client_id' => settings($platform.'_client_id'),
                $platform.'_client_secret' => settings($platform.'_client_secret'),
            ])
            ->toArray();
    }

    // get platforms property
    public function getPlatformsProperty(): array
    {
        return collect(config('atom.auth.login', []))
            ->reject('email')
            ->reject('username')
            ->toArray();
    }

    // get platform labels property
    public function getPlatformLabelsProperty(): array
    {
        return [
            'google' => 'Google',
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'github' => 'Github',
            'twitter-oauth-2' => 'Twitter',
        ];
    }

    // get client id label property
    public function getClientIdLabelsProperty(): array
    {
        return [
            'google' => 'Client ID',
            'facebook' => 'App ID',
            'linkedin' => 'Client ID',
            'github' => 'Client ID',
            'twitter_oauth_2' => 'Consumer Key',
        ];
    }

    // get client secret label property
    public function getClientSecretLabelsProperty(): array
    {
        return [
            'google' => 'Client Secret',
            'facebook' => 'App Secret',
            'linkedin' => 'Client Secret',
            'github' => 'Client Secret',
            'twitter_oauth_2' => 'Consumer Secret',
        ];
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup('Social Login Credentials Updated.');
    }
}