<div 
    x-data="{ show: false }"
    x-on:click="show = !show"
    x-on:click.outside="show = false"
    class="relative cursor-pointer flex flex-col gap-1 items-center justify-center {{ $attributes->get('class') ?? 'text-gray-900 hover:text-theme' }}"
>
    <x-icon name="language" size="18px"/>
    <div class="text-center" style="font-size: 0.6rem;">
        {{ __('Language') }}
    </div>

    <div x-show="show" class="absolute bottom-full border rounded-md bg-white shadow w-max grid divide-y">
        @foreach (config('atom.locales') as $locale)
            <a href="/{{ $locale }}" class="py-2 px-4 text-sm text-gray-900">
                {{ data_get(metadata('locales', $locale), 'name') }}
            </a>
        @endforeach
    </div>
</div>
