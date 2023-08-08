@props([
    'size' => $attributes->get('size', 'md'),
    'providers' => collect(config('atom.auth.login'))
        ->filter(function($provider) {
            $clientId = settings($provider.'_client_id') ?? env(strtoupper($provider).'_CLIENT_ID');
            $clientSecret = settings($provider.'_client_secret') ?? env(strtoupper($provider).'_CLIENT_SECRET');
            return !empty($clientId) && !empty($clientSecret);
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
        })
])

@if ($providers->count())
    <div class="flex flex-col">
        @if ($divider = $attributes->get('divider'))
            <div class="flex items-center gap-3 py-6 {{ 
                $attributes->get('divider-position') === 'bottom' ? 'order-last' : '' 
            }}">
                <div class="grow bg-gray-300 h-px"></div>
                <div class="text-sm text-gray-400 font-medium">{{ __($divider) }}</div>
                <div class="grow bg-gray-300 h-px"></div>
            </div>
        @endif
    
        <div class="flex flex-col gap-2">
            @foreach ($providers as $key => $value)
                <x-button
                    :label="__('Continue with :social', ['social' => data_get($value, 'label')])"
                    :icon="$key.' brands'"
                    :color="$key"
                    :size="$size"
                    :href="route('socialite.redirect', array_merge(
                        ['provider' => $key],
                        request()->query(),
                    ))"
                />
            @endforeach
        </div>
    </div>
@endif