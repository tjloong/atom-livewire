<?php

namespace Jiannius\Atom\Actions;

use Laravel\Socialite\Facades\Socialite;

class GetSocialiteUser
{
    public function __construct(public $params)
    {
        //
    }

    public function run()
    {
        $token = get($this->params, 'token');
        $provider = get($this->params, 'provider');

        if (!$token || !$provider) return;

        $socialite = Socialite::driver($provider)->userFromToken($token);
        $email = $socialite?->getEmail();

        if (!$email) return;

        return model('user')->firstOrNew(['email' => $email], [
            'name' => $socialite->getName(),
            'email' => $socialite->getEmail(),
            'password' => str()->snake($socialite->getName()).'_oauth',
            'email_verified_at' => now(),
            'agree_tnc' => true,
            'agree_promo' => true,
            'data' => ['oauth' => [
                'provider' => $provider,
                'id' => $socialite->getId(),
                'nickname' => $socialite->getNickname(),
                'avatar' => $socialite->getAvatar(),
                'token' => $socialite->token,
                'token_secret' => $socialite->tokenSecret,
                'refresh_token' => $socialite->refreshToken,
                'expires_in' => $socialite->expiresIn,
            ]],
        ]);
    }
}
