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
        <label class="normal-case font-normal text-base text-gray-800" @disabled($disabled)>
            <div class="flex gap-3">
                <div class="shrink-0 py-0.5">
                    <input type="checkbox" class="hidden peer" {{ $attributes }}>
                    <div class="
                        bg-white text-white border border-gray-300 rounded flex 
                        peer-checked:bg-theme peer-checked:border-0 peer-checked:ring-theme peer-checked:ring-1 peer-checked:ring-offset-1
                        {{ $attributes->get('sm') ? 'w-4 h-4' : 'w-5 h-5' }}">
                        <x-icon name="check" class="m-auto text-xs"/>
                    </div>
                </div>

                @if ($slot->isNotEmpty())
                    <div {{ $attributes->only('class') }}>
                        {{ $slot }}
                    </div>
                @elseif ($label)
                    <div class="py-0.5 text-base font-normal normal-case text-gray-800 {{ $attributes->get('class') }}">
                        {!! tr($label) !!}
                    </div>
                @endif
            </div>

            @isset($caption)
                <div class="normal-case px-1" style="margin-left: 1.75rem">
                    {{ $caption }}
                </div>
            @elseif ($caption = $attributes->get('small') ?? $attributes->get('caption'))
                <div class="text-sm text-gray-500 font-medium normal-case px-1" style="margin-left: 1.75rem">
                    {!! tr($caption) !!}
                </div>
            @endisset
        </label>
    </div>
@endif
