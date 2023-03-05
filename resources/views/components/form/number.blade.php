<x-form.field {{ $attributes }}>
    <div 
        x-data="{ focus: false }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ 
            component_error(optional($errors), $attributes) ? 'error' : ''
        }}"
    >
        <input type="number"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            {{ $attributes->class([
                'appearance-none bg-transaprent border-0 p-0 focus:ring-0 w-full'
            ])->except(['error', 'caption']) }}
        >

        @if ($unit = $attributes->get('unit') ?? $attributes->get('postfix'))
            <div class="font-medium text-gray-500">{{ __($unit) }}</div>
        @endif
    </div>
</x-form.field>