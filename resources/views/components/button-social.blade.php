@if (($settings = model('setting')->getSocialLogins()) && $settings->count())
    <div class="flex flex-col gap-2">
    @foreach ($settings as $setting)
        <x-button :action="get($setting, 'name')" {{ $attributes->only(['outline', 'invert', 'ghost']) }}/>
    @endforeach
    </div>
@endif