<div
    class="relative"
    style="height: {{ $size }}px; width: {{ $size }}px;"
>
    <figure
        {{ $attributes
            ->merge(['class' => 'rounded-full shadow flex w-full h-full overflow-hidden']) 
            ->except(['url', 'placeholder', 'wire:remove'])
        }}
        style="background-color: {{ collect($colors)->random() }};"
    >
        @if ($url = $attributes->get('url'))
            <img src="{{ $url }}" class="w-full h-full object-cover">
        @elseif ($placeholder = $attributes->get('placeholder'))
            <div class="m-auto text-white font-bold" style="font-size: {{ $size/3.2 }}px">
                {{ str($placeholder)->substr(0, 2)->upper() }}
            </div>
        @endif
    </figure>

    @if ($attributes->has('wire:remove'))
        <a 
            class="absolute top-0 -right-4 w-8 h-8 bg-white border shadow flex rounded-full text-red-500"
            wire:click="{{ $attributes->get('wire:remove') }}"
        >
            <x-icon name="xmark" class="m-auto" size="18px"/>
        </a>
    @endif
</div>
