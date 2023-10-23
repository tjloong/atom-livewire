@if ($attributes->has('options') && ($options = $attributes->get('options', [])))
    <x-form.checkbox.multiple {{ $attributes }}/>
@else
    @php
        $label = $attributes->get('label');
        $disabled = $attributes->get('disabled', false);
    @endphp

    <div x-cloak
        x-data="{ disabled: @js($disabled) }"
        x-bind:class="disabled && 'opacity-30 pointer-events-none'">
        <label @disabled($disabled)>
            <div class="flex items-center gap-3">
                <div class="shrink-0">
                    <input type="checkbox" class="hidden peer" {{ $attributes }}>
                    <div class="w-4 h-4 bg-white text-white ring-1 ring-gray-300 ring-offset-2 rounded flex peer-checked:bg-theme peer-checked:ring-theme peer-checked:ring-1">
                        <x-icon name="check" class="m-auto text-xs"/>
                    </div>
                </div>

                @if ($slot->isNotEmpty())
                    <div {{ $attributes->class(['font-normal'])->only('class') }}>
                        {{ $slot }}
                    </div>
                @elseif ($label)
                    <div class="py-0.5 text-base font-normal normal-case text-gray-800 {{ $attributes->get('class') }}">
                        {!! tr($label) !!}
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
