<?php

namespace Jiannius\Atom\Components\Button;

use Illuminate\View\Component;

class SocialLogin extends Component
{
    public $size;
    public $providers;

    /**
     * Constructor
     */
    public function __construct($size = 'sm')
    {
        $this->size = $size;
        $this->providers = collect(config('atom.auth.login'))
            ->filter(function($provider) {
                $clientId = settings($provider.'_client_id') ?? env(strtoupper($provider).'_CLIENT_ID');
                $clientSecret = settings($provider.'_client_secret') ?? env(strtoupper($provider).'_CLIENT_SECRET');
                return $clientId && $clientSecret;
            })
            ->mapWithKeys(function($provider) {
                return [$provider => [
                    'label' => [
                        'google' => 'Google',
                        'facebook' => 'Facebook',
                        'linkedin' => 'LinkedIn',
                        'twitter' => 'Twitter',
                        'twitter-oauth-2' => 'Twitter',
                        'github' => 'Github',
                    ][$provider],
                    'class' => [
                        'google' => 'bg-rose-500 text-white',
                        'facebook' => 'bg-blue-600 text-white',
                        'linkedin' => 'bg-sky-600 text-white',
                        'twitter' => 'bg-sky-400 text-white',
                        'twitter-oauth-2' => 'bg-sky-400 text-white',
                        'github' => 'bg-black text-white',
                    ][$provider],
                ]];
            });
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.button.social-login');
    }
}