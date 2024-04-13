@php
    $settings = model('setting')->getSocialLogins();
@endphp

@if ($settings->count())
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
@endif