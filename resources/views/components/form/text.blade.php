<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ __($label) }}</x-slot:label>
    @endif

    @if ($attributes->has('transparent'))
        <div class="relative" x-data>
            <input 
                x-ref="input" 
                type="text" 
                class="" 
                {{ $attributes->class([
                    'w-full border-0 p-0 pr-10',
                    empty($attributes->get('error')) ? 'focus:ring-0' : 'ring-red-500 ring-offset-2',
                ]) }}
            >
            <a 
                x-on:click.prevent="$refs.input.select()"
                class="absolute top-0 right-0 bottom-0 flex justify-center items-center text-gray-400" 
            >
                <x-icon name="pencil" size="18px"/>
            </a>
        </div>
    @else
        <input type="text" {{ $attributes->class([
            'form-input w-full',
            'error' => !empty($attributes->get('error')),
        ]) }}>
    @endif
</x-form.field>
