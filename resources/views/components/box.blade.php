<div {{ $attributes->merge(['class' => 'box bg-white rounded-md border shadow']) }}">
    <div class="p-1">
        @isset($header)
        <div class="pt-3 pb-4 px-3 border-b font-bold text-base">
            {{ $header }}
        </div>
        @endisset

        {{ $slot }}
    </div>

    @isset ($buttons)
    <div class="bg-gray-100 p-4 rounded-b-md">
        {{ $buttons }}
    </div>
    @endisset
</div>