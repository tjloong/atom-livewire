<figure
    class="rounded-full shadow flex"
    style="height: {{ $size }}px; width: {{ $size }}px; background-color: {{ collect($colors)->random() }};"
>
    @if ($url = $attributes->get('url'))
        <img src="{{ $url }}" class="w-full h-full object-cover">
    @elseif ($placeholder = $attributes->get('placeholder'))
        <div class="m-auto text-white font-bold" style="font-size: {{ $size/2.5 }}px">
            {{ str($placeholder)->substr(0, 1)->upper() }}
        </div>
    @endif
</figure>