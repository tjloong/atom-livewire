<a
    class="bg-gray-300 text-gray-800 rounded py-1 px-2 inline-flex items-center justify-center mr-3"
    @if ($href = $attributes->get('href')) href="{{ $href }}"
    @else x-data x-on:click.prevent="history.back()"
    @endif
>
    <x-icon name="left-arrow-alt"/>
</a>
