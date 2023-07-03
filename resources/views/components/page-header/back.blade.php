@props(['size' => $attributes->get('size')])

<a
    class="bg-gray-300 text-gray-800 rounded inline-flex items-center justify-center my-2 mr-4 {{ 
        $size === 'sm'
        ? 'w-8 h-8'
        : 'w-10 h-10'
    }}"
    {{ $attributes->except('size') }}
>
    <x-icon name="back"/>
</a>
