<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class SocialLogin extends Component
{
    use WithForm;
    use WithPopupNotify;
    
    public $platform;
    public $settings;

    /**
     * Validation
     */
    public function validation(): array
    {
        return [
            'settings.'.$this->platform.'_client_id' => ['nullable'],
            'settings.'.$this->platform.'_client_secret' => ['nullable'],

        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->settings = collect($this->platforms)
            ->mapWithKeys(fn($val) => [
                $val.'_client_id' => settings($val.'_client_id'),
                $val.'_client_secret' => settings($val.'_client_secret'),
            ])
            ->toArray();

        $this->platform = head($this->platforms);
    }

    /**
     * Get platforms property
     */
    public function getPlatformsProperty(): array
    {
        return config('atom.auth.login', []);
    }

    /**
     * Get platform labels property
     */
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

    /**
     * Get client id label property
     */
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

    /**
     * Get client secret label property
     */
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

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        settings([
            $this->platform.'_client_id' => data_get($this->settings, $this->platform.'_client_id'),
            $this->platform.'_client_secret' => data_get($this->settings, $this->platform.'_client_secret'),
        ]);

        $this->popup(data_get($this->platformLabels, $this->platform, str()->headline($this->platform)).' Credential Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.integration.social-login');
    }
}