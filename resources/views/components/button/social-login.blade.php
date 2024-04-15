@php
    $settings = model('setting')->getSocialLogins();
@endphp

@if ($settings->count())
    <div class="flex flex-col gap-2">
        @foreach ($settings as $setting)
            <x-button
                :label="tr('app.label.continue-with-social-login', ['provider' => get($setting, 'label')])"
                :icon="get($setting, 'name').' brands'"
                :color="get($setting, 'name')"
                :href="route('socialite.redirect', array_merge(
                    ['provider' => get($setting, 'name')],
                    request()->query(),
                ))"/>
        @endforeach
    </div>
@endif