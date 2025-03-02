@php
$enabled = app('route')->has('socialite.redirect') && app('route')->has('socialite.callback');

$brands = $enabled ? [
    [
        'slug' => 'bitbucket',
        'name' => 'Bitbucket',
        'icon' => '',
        'color' => '',
    ],
    [
        'slug' => 'facebook',
        'name' => 'Facebook',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.001 2C6.47813 2 2.00098 6.47715 2.00098 12C2.00098 16.9913 5.65783 21.1283 10.4385 21.8785V14.8906H7.89941V12H10.4385V9.79688C10.4385 7.29063 11.9314 5.90625 14.2156 5.90625C15.3097 5.90625 16.4541 6.10156 16.4541 6.10156V8.5625H15.1931C13.9509 8.5625 13.5635 9.33334 13.5635 10.1242V12H16.3369L15.8936 14.8906H13.5635V21.8785C18.3441 21.1283 22.001 16.9913 22.001 12C22.001 6.47715 17.5238 2 12.001 2Z"></path></svg>',
        'color' => 'text-blue-500',
    ],
    [
        'slug' => 'google',
        'name' => 'Google',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.06364 7.50914C4.70909 4.24092 8.09084 2 12 2C14.6954 2 16.959 2.99095 18.6909 4.60455L15.8227 7.47274C14.7864 6.48185 13.4681 5.97727 12 5.97727C9.39542 5.97727 7.19084 7.73637 6.40455 10.1C6.2045 10.7 6.09086 11.3409 6.09086 12C6.09086 12.6591 6.2045 13.3 6.40455 13.9C7.19084 16.2636 9.39542 18.0227 12 18.0227C13.3454 18.0227 14.4909 17.6682 15.3864 17.0682C16.4454 16.3591 17.15 15.3 17.3818 14.05H12V10.1818H21.4181C21.5364 10.8363 21.6 11.5182 21.6 12.2273C21.6 15.2727 20.5091 17.8363 18.6181 19.5773C16.9636 21.1046 14.7 22 12 22C8.09084 22 4.70909 19.7591 3.06364 16.4909C2.38638 15.1409 2 13.6136 2 12C2 10.3864 2.38638 8.85911 3.06364 7.50914Z"></path></svg>',
        'color' => 'text-red-500',
    ],
    [
        'slug' => 'linkedin_openid',
        'name' => 'LinkedIn',
        'icon' => '',
        'color' => '',
    ],
    [
        'slug' => 'github',
        'name' => 'Github',
        'icon' => '',
        'color' => '',
    ],
    [
        'slug' => 'gitlab',
        'name' => 'Gitlab',
        'icon' => '',
        'color' => '',
    ],
    [
        'slug' => 'slack',
        'name' => 'Slack',
        'icon' => '',
        'color' => '',
    ],
    [
        'slug' => 'twitter',
        'name' => 'Twitter',
        'icon' => '',
        'color' => '',
    ],
    [
        'slug' => 'twitter_oauth_2',
        'name' => 'Twitter',
        'icon' => '',
        'color' => '',
    ],
] : [];

$brands = collect($brands)
    ->map(fn($brand) => [
        ...$brand,
        'client_id' => env(strtoupper(get($brand, 'slug').'_client_id')),
        'client_secret' => env(strtoupper(get($brand, 'slug').'_client_secret')),
        'redirect' => route('socialite.redirect', ['provider' => get($brand, 'slug'), ...request()->query()]),
        'callback' => route('socialite.callback', ['provider' => get($brand, 'slug')]),
    ])
    ->filter(fn($val) =>
        !empty(get($val, 'client_id')) && !empty(get($val, 'client_secret'))
    )
    ->values();

foreach ($brands as $brand) {
    config(['services.'.get($brand, 'slug') => [
        'client_id' => get($brand, 'client_id'),
        'client_secret' => get($brand, 'client_secret'),
        'redirect' => get($brand, 'callback'),
    ]]);
}

$separatorTop = $attributes->get('separator-top');
$separatorBottom = $attributes->get('separator-bottom');
@endphp

@if ($brands->count())
<div class="space-y-6">
    @if ($separatorTop)
        <atom:separator>@e($separatorTop)</atom:separator>
    @endif

    <div class="space-y-3">
        @foreach ($brands as $brand)
            <a
                href="{!! get($brand, 'redirect') !!}"
                rel="noopener noreferrer nofollow"
                class="w-full flex items-center gap-4 h-12 rounded-lg border border-zinc-300 bg-white px-6 hover:bg-zinc-50">
                <div class="shrink-0 flex items-center justify-center">
                    <div style="width: 20px; height: 20px" class="{{ get($brand, 'color') }}">
                        @ee(get($brand, 'icon'))
                    </div>
                </div>

                <div class="grow font-medium text-left">
                    @t('continue-with-social-login', ['provider' => get($brand, 'name')])
                </div>

                <div class="shrink-0 flex items-center justify-center">
                    <atom:icon arrow-right/>
                </div>
            </a>        
        @endforeach
    </div>

    @if ($separatorBottom)
        <atom:separator>@e($separatorBottom)</atom:separator>
    @endif
</div>
@endif
