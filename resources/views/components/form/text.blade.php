@props([
    'prefix' => $prefix ?? $attributes->get('prefix'),
    'postfix' => $postfix ?? $attributes->get('postfix'),
    'clear' => $attributes->get('clear', false),
    'placeholder' => $attributes->get('placeholder'),
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{ focus: false }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ $attributes->get('readonly') ? 'readonly' : '' }}">
        @if (is_string($prefix))
            @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($prefix) }}</div>
            @endif
        @else {{ $prefix }}
        @endif

        <div class="grow">
            <input type="text" 
                class="w-full bg-transparent"
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                {{ $attributes
                    ->class(['form-input transparent w-full'])
                    ->merge([
                        'placeholder' => $placeholder === 'autogen'
                            ? 'Leave empty to auto generate'
                            : __($placeholder),
                    ])
                    ->except(['error', 'required', 'caption', 'label', 'label-tag', 'prefix', 'postfix'])
                }}
            >
        </div>

        @if ($clear)
            @if ($wire = $attributes->whereStartsWith('wire:model')->first()) 
                <x-close wire:click="$set('{{ $wire }}', null)" class="-m-1"/>
            @else 
                <x-close x-on:click="$dispatch('clear')" class="-m-1"/>
            @endif
        @else
            @if (is_string($postfix))
                @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
                @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
                @endif
            @elseif ($postfix)
                {{ $postfix }}
            @endif

            @isset($button)
                @php $label = $button->attributes->get('label') @endphp 
                @php $icon = $button->attributes->get('icon') @endphp 
                <a {{ $button->attributes->class([
                    'flex items-center justify-center gap-2 rounded-full -mr-1 text-sm',
                    $label ? 'px-2 py-0.5' : null,
                    !$label && $icon ? 'p-1' : null,
                    $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
                ]) }}">
                    @if ($icon) <x-icon :name="$icon" size="11"/> @endif
                    {{ __($label) }}
                </a>
            @endisset
        @endif
    </div>
</x-form.field>
