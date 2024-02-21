@php
    $step = $attributes->get('step', 'any');
    $placeholder = $attributes->get('placeholder');
@endphp

<x-form.field {{ $attributes }}>
    <div 
        x-data="{ focus: false }"
        x-on:click="focus = true"
        x-on:click.away="focus = false"
        x-bind:class="focus && 'active'"
        class="form-input w-full p-0 flex items-center divide-x divide-gray-300">
        <div class="flex items-center gap-2 grow py-1.5 px-3">
            @if (isset($prefix)) {{ $prefix }}
            @elseif ($prefix = $attributes->get('prefix')) <div class="shrink-0 text-gray-500 font-medium">{{ tr($prefix) }}</div>
            @elseif ($icon = $attributes->get('icon')) <x-icon :name="$icon" class="text-gray-400"/>
            @endif
    
            <input type="number" class="transparent w-full grow"
                step="{{ $step }}"
                placeholder="{{ tr($placeholder) }}"
                {{ $attributes->except(['prefix', 'icon', 'postfix', 'unit', 'step', 'placeholder']) }}>
    
            @if (isset($suffix)) {{ $suffix }}
            @elseif ($suffix = $attributes->get('suffix') ?? $attributes->get('unit'))
                <div class="shrink-0 text-gray-500 font-medium">{{ tr($suffix) }}</div>
            @endif
        </div>

        @isset($button)
            @php
                $label = $button->attributes->get('label');
                $icon = $button->attributes->get('icon');
            @endphp

            @if ($label || $icon)
                <button type="button" {{ $button->attributes->class([
                    'py-1.5 px-2 flex items-center justify-center gap-2 text-sm text-gray-500',
                ]) }}>
                    @if ($icon) <x-icon :name="$icon"/> @endif
                    @if ($label) {{ tr($label )}} @endif
                </button>
            @else
                {{ $button }}
            @endif
        @endisset
    </div>
</x-form.field>