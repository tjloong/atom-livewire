<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class SocialLogin extends Component
{
    use WithPopupNotify;
    
    public $settings;

    /**
     * Mount
     */
    public function mount()
    {
        $this->settings = collect($this->providers)
            ->mapWithKeys(fn($val) => [
                $val.'_client_id' => settings($val.'_client_id'),
                $val.'_client_secret' => settings($val.'_client_secret'),
            ])
            ->toArray();
    }

    /**
     * Get providers property
     */
    public function getProvidersProperty()
    {
        return config('atom.auth.login', []);
    }

    /**
     * Get provider labels property
     */
    public function getProviderLabelsProperty()
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
    public function getClientIdLabelsProperty()
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
    public function getClientSecretLabelsProperty()
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
    public function submit($provider)
    {
        $this->resetValidation();

        $this->validate([
            'settings.'.$provider.'_client_id' => 'required',
            'settings.'.$provider.'_client_secret' => 'required',
        ], [
            'settings.'.$provider.'_client_id.required' => __(data_get($this->clientIdLabels, $provider, 'Client ID').' is required.'),
            'settings.'.$provider.'_client_secret.required' => __(data_get($this->clientSecretLabels, $provider, 'Client Secret').' is required.'),
        ]);

        settings([
            $provider.'_client_id' => data_get($this->settings, $provider.'_client_id'),
            $provider.'_client_secret' => data_get($this->settings, $provider.'_client_secret'),
        ]);

        $this->popup(data_get($this->providerLabels, $provider, str()->headline($provider)).' Credential Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.integration.social-login');
    }
}