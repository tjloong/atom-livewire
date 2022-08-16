<div class="box bg-white border shadow {{ $attributes->get('class', 'rounded-md') }}">
    <div class="p-1">
        @if (isset($header))
            <div class="pt-3 pb-4 px-3 border-b font-bold {{ $header->attributes->get('class', 'text-lg') }}">
                {{ $header }}
            </div>
        @elseif ($header = $attributes->get('header'))
            <div class="pt-3 pb-4 px-3 border-b font-bold text-lg">
                {{ __($header) }}
            </div>
        @endif

        {{ $slot }}
    </div>

    @isset ($buttons)
        <div class="bg-gray-100 p-4 rounded-b-md">
            {{ $buttons }}
        </div>
    @endisset
</div>
