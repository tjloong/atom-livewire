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
            class="appearance-none bg-transaprent border-0 p-0 focus:ring-0 w-full"
            {{ $attributes->except(['error', 'caption']) }}
        >

        @isset($postfix) {{ $postfix }}
        @elseif ($postfix = $attributes->get('postfix') ?? $attributes->get('unit'))
            @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
            @endif
        @endif

        @isset($button)
            @php $label = $button->attributes->get('label') @endphp 
            @php $icon = $button->attributes->get('icon') @endphp 
            <a {{ $button->attributes->class([
                'flex items-center justify-center gap-1 rounded-full -mr-1 text-sm',
                $label ? 'px-2 py-0.5' : null,
                !$label && $icon ? 'p-1' : null,
                $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
            ]) }}">
                @if ($icon) <x-icon :name="$icon" size="12"/> @endif
                {{ __($label) }}
            </a>
        @endisset
    </div>
</x-form.field>