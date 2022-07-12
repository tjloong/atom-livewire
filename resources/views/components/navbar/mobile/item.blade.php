<a 
    href="{{ $attributes->get('href') }}"
    class="flex flex-col gap-1 items-center justify-center {{ $attributes->get('class') ?? 'text-gray-900 hover:text-theme' }}"
>
    <x-icon :name="$attributes->get('icon')" size="18px"/>
    <div class="text-center" style="font-size: 0.6rem;">
        @if ($label = $attributes->get('label')) {{ __($label) }} @endif
    </div>
</a>
