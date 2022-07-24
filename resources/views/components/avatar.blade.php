<figure
    class="rounded-full shadow flex overflow-hidden"
    style="height: {{ $size }}px; width: {{ $size }}px; background-color: {{ collect($colors)->random() }};"
>
    @if ($url = $attributes->get('url'))
        <img src="{{ $url }}" class="w-full h-full object-cover">
    @elseif ($placeholder = $attributes->get('placeholder'))
        <div class="m-auto text-white font-bold" style="font-size: {{ $size/3.2 }}px">
            {{ str($placeholder)->substr(0, 2)->upper() }}
        </div>
    @endif
</figure>