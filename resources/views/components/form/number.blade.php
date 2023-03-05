<x-form.field {{ $attributes }}>
    <div 
        x-data="{ focus: false }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ 
            component_error(optional($errors), $attributes) ? 'error' : ''
        }}"
    >
        @isset($prefix) {{ $prefix }}
        @elseif ($prefix = $attributes->get('prefix'))
            @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($prefix) }}</div>
            @endif
        @endif

        <input type="number"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            {{ $attributes->class([
                'appearance-none bg-transaprent border-0 p-0 focus:ring-0 w-full'
            ])->except(['error', 'caption']) }}
        >

        @isset($postfix) {{ $postfix }}
        @elseif ($postfix = $attributes->get('postfix') ?? $attributes->get('unit'))
            @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
            @endif
        @endif
    </div>
</x-form.field>