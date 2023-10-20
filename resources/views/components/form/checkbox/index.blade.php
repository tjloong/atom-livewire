@if ($attributes->has('options') && ($options = $attributes->get('options', [])))
    <x-form.checkbox.multiple {{ $attributes }}/>
@else
    @props([
        'disabled' => $attributes->get('disabled', false),
        'label' => $attributes->get('label'),
        'id' => component_id($attributes, 'checkbox'),
    ])

    <div
        x-cloak
        x-data="{
            disabled: @js($disabled),
            get active () {
                return this.$refs.checkbox?.checked
            },
        }"
        id="{{ $id }}"
        class="{{ $disabled ? 'opacity-30 pointer-events-none' : null }}"
    >
        <label @disabled($disabled)>
            <input x-ref="checkbox" type="checkbox" class="hidden" {{ $attributes }}>

            <div class="flex gap-2 items-center">
                <div x-ref="box"
                    x-bind:class="active ? 'border-blue-700' : 'border-gray-300'"
                    class="shrink-0 w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded"
                >
                    <div class="w-3 h-3 shadow bg-blue-700" x-show="active"></div>
                </div>

                @if ($slot->isNotEmpty())
                    <div {{ $attributes->class(['font-normal'])->only('class') }}>
                        {{ $slot }}
                    </div>
                @elseif ($label)
                    <div class="py-0.5 text-base font-normal normal-case text-gray-800 {{ $attributes->get('class') }}">
                        {!! __($label) !!}
                    </div>
                @endif
            </div>

            @isset($small)
                <div class="normal-case ml-8 px-1">
                    {{ $small }}
                </div>
            @elseif ($small = $attributes->get('small') ?? $attributes->get('caption'))
                <div class="text-sm text-gray-500 font-medium normal-case ml-8 px-1">
                    {!! __($small) !!}
                </div>
            @endisset
        </label>
    </div>
@endif
