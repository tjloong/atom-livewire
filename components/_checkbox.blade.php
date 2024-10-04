@php
$field = $attributes->field();
$label = $attributes->get('label');
$caption = $attributes->get('caption');
@endphp

<div class="group inline-block">
    <label class="inline-flex gap-3">
        <div class="shrink-0">
            <input type="checkbox" class="hidden peer" {{ $attributes->except('class') }}>

            <button
                type="button"
                class="w-5 h-5 bg-white rounded-md border border-gray-300 shadow-sm flex items-center justify-center ring-offset-1 focus:outline-none focus:ring-1 focus:ring-theme peer-checked:bg-theme peer-checked:border-theme group-has-[.error]:ring-1 group-has-[.error]:ring-red-500"
                x-on:click.stop="$el.parentNode.querySelector('input').click()">
                <x-icon name="check" class="text-gray-300 text-xs group-has-[:checked]:text-white"/>
            </button>
        </div>    

        @if ($label)
            <div class="grow space-y-0.5">
                <div class="leading-tight tracking-wide">
                    {!! tr($label) !!}
                </div>

                @if ($caption)
                    <div class="text-sm text-gray-500">
                        {!! tr($caption) !!}
                    </div>
                @endif

                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @endif
            </div>
        @elseif ($slot->isNotEmpty())
            <div class="grow">
                {{ $slot }}
            </div>
        @endif
    </label>

    @if ($field)
        <x-error :field="$field" class="mt-2"/>
    @endif
</div>