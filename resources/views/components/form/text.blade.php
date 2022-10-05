@props([
    'prefix' => $prefix ?? $attributes->get('prefix'),
    'postfix' => $postfix ?? $attributes->get('postfix'),
    'clear' => $attributes->get('clear', false),
])

<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label', 'label-tag']) }}>
    <div 
        x-data="{ focus: false }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ !empty($attributes->get('error')) ? 'error' : '' }}"
    >
        @if (is_string($prefix))
            @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($prefix) }}</div>
            @endif
        @else {{ $prefix }}
        @endif

        <div class="grow">
            <input type="text" 
                class="w-full"
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                {{ $attributes
                    ->class(['form-input transparent w-full'])
                    ->merge([
                        'placeholder' => __($attributes->get('placeholder')),
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
        @elseif (is_string($postfix))
            @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
            @endif
        @else {{ $postfix }}
        @endif
    </div>
</x-form.field>
