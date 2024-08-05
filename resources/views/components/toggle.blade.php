@php
$field = $attributes->field();
$label = $attributes->get('label');
$caption = $attributes->get('caption');
@endphp

<div class="group inline-block focus:outline-none">
    <label class="inline-flex gap-3">
        <div class="shrink-0">
            <input type="checkbox" class="hidden peer" {{ $attributes->except('class') }}>

            <button
                type="button"
                class="bg-gray-200 rounded-full flex items-center px-px shadow-inner ring-offset-1 focus:outline-none focus:ring-1 focus:ring-theme peer-checked:bg-theme group-has-[.error]:ring-red-500 group-has-[.error]:ring-1"
                style="width: 38px; height: 20px;"
                x-on:click.stop="$el.parentNode.querySelector('input').click()">
                <div
                    class="rounded-full bg-white border shadow transition-transform duration-100 ease-in-out group-has-[:checked]:translate-x-full"
                    style="width: 18px; height: 18px">
                </div>
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