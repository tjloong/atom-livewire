
@if ($attributes->has('options') && ($options = $attributes->get('options', [])))
    <x-form.checkbox.multiple {{ $attributes }}/>
@else
    @props([
        'disabled' => $attributes->get('disabled', false),
        'label' => $attributes->get('label'),
        'small' => $attributes->get('small') ?? $attributes->get('caption'),
        'id' => component_id($attributes, 'checkbox'),
    ])

    <div
        x-cloak
        x-data="{
            active: false,
            disabled: @js($disabled),
            toggle () {
                if (this.disabled) return
                this.active = this.$refs.checkbox?.checked
            },
        }"
        id="{{ $id }}"
        class="{{ $disabled ? 'opacity-30' : null }}"
    >
        <label 
            x-init="toggle"
            x-on:input="toggle"
            class="flex gap-2" 
            @disabled($disabled)
        >
            <input x-ref="checkbox" type="checkbox" class="hidden" {{ $attributes }}>

            <div x-ref="box"
                x-bind:class="active ? 'border-blue-700' : 'border-gray-300'"
                class="shrink-0 w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded"
            >
                <div class="w-3 h-3 shadow bg-blue-700" x-show="active"></div>
            </div>

            @if ($slot->isNotEmpty())
                <div {{ $attributes->class(['font-normal'])->only('class') }}>{{ $slot }}</div>
            @elseif ($label && $small)
                <div class="grid py-0.5 {{ $attributes->get('class') }}">
                    <div class="font-normal">{!! __($label) !!}</div>
                    <div class="span text-sm text-gray-500 font-medium">{!! __($small) !!}</div>
                </div>
            @elseif ($label)
                <div class="py-0.5 font-normal {{ $attributes->get('class') }}">{!! __($label) !!}</div>
            @endif
        </label>
    </div>
@endif
