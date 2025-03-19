<?php

namespace Jiannius\Atom\Actions;

class ConfigureSocialite
{
    public function __construct(public $provider)
    {
        //
    }

    public function run()
    {
        $clientId = env(strtoupper($this->provider.'_client_id'));
        $clientSecret = env(strtoupper($this->provider.'_client_secret'));

        if (!$clientId || !$clientSecret) return false;

        config(['services.'.$this->provider => [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect' => route('socialite.callback', ['provider' => $this->provider]),
        ]]);

        return true;
    }
}