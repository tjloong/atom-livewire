@php
    $divider = $attributes->get('divider');    
    $providers = collect([
        'google' => 'Google',
        'facebook' => 'Facebook',
        'linkedin' => 'LinkedIn',
        'twitter' => 'Twitter',
        'twitter-oauth-2' => 'Twitter',
        'github' => 'Github',
    ])
    ->map(fn($label, $name) => [
        'name' => $name,
        'label' => $label,
        'client_id' => settings($name.'_client_id'),
        'client_secret' => settings($name.'_client_secret'),
    ])
    ->filter(fn($val) => !empty(data_get($val, 'client_id')) && !empty(data_get($val, 'client_secret')))
    ->values();
@endphp

@if ($providers->count())
    <div class="flex flex-col">
        @if ($divider) <x-divider :label="$divider"/> @endif

        <div class="flex flex-col gap-2">
            @foreach ($providers as $provider)
                <x-button
                    :label="tr('app.label.continue-with-social-login', ['provider' => data_get($provider, 'label')])"
                    :icon="data_get($provider, 'name').' brands'"
                    :color="data_get($provider, 'name')"
                    :href="route('socialite.redirect', array_merge(
                        ['provider' => data_get($provider, 'name')],
                        request()->query(),
                    ))"/>
            @endforeach
        </div>
    </div>
@endif