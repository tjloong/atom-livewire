<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot name="label">{{ $slot }}</x-slot>
    @endif

    @if ($attributes->has('transparent'))
        <div class="relative" x-data>
            <input type="text" x-ref="input" class="w-full border-0 p-0 pr-10 focus:ring-0" {{ $attributes }}>
            <a class="absolute top-0 right-0 bottom-0 flex justify-center items-center text-gray-400" x-on:click.prevent="$refs.input.select()">
                <x-icon name="pencil" size="18px"/>
            </a>
        </div>
    @else
        <input type="text" {{ $attributes->class(['form-input w-full']) }}>
    @endif
</x-input.field>