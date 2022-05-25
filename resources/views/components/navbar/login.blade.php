@if (count($links))
    <div class="flex items-center justify-center gap-3">
        @foreach ($links as $link)
            @if (data_get($link, 'button'))
                <x-button :href="data_get($link, 'href')" :label="data_get($link, 'placeholder')"/>
            @else
                <x-navbar.item :href="data_get($link, 'href')" :label="data_get($link, 'placeholder')"/>
            @endif
        @endforeach
    </div>
@endif
