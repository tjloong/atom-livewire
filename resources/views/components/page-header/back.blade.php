<a
    class="bg-gray-300 text-gray-800 rounded w-10 h-10 inline-flex items-center justify-center"
    @if ($href = $attributes->get('href')) href="{{ $href }}"
    @else x-data x-on:click.prevent="history.back()"
    @endif
>
    <x-icon name="left-arrow-alt"/>
</a>
