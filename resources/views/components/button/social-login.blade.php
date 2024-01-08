@php
    $providers = collect(config('atom.auth.login'))
        ->reject(fn($val) => in_array($val, ['email', 'email-verified', 'username']))
        ->reject(fn($val) => !settings($val.'_client_id') || !settings($val.'_client_secret'))
        ->mapWithKeys(fn($val) => [$val => [
            'google' => 'Google',
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter',
            'twitter-oauth-2' => 'Twitter',
            'github' => 'Github',
        ][$val]]);
@endphp

@if ($providers->count())
    <div class="flex flex-col gap-2">
        @foreach ($providers as $key => $value)
            <x-button
                :label="tr('app.label.continue-with-social-login', ['provider' => $value])"
                :icon="$key.' brands'"
                :color="$key"
                :href="route('socialite.redirect', array_merge(
                    ['provider' => $key],
                    request()->query(),
                ))"/>
        @endforeach
    </div>
@endif