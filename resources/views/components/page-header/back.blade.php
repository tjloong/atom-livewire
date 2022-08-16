@props(['size' => $attributes->get('size')])

<a
    class="bg-gray-300 text-gray-800 rounded inline-flex items-center justify-center {{ 
        $size === 'sm'
        ? 'w-8 h-8'
        : 'w-10 h-10'
    }}"
    @if ($href = $attributes->get('href')) href="{{ $href }}"
    @else x-data x-on:click.prevent="history.back()"
    @endif
>
    <x-icon name="left-arrow-alt"/>
</a>
