@php
$field = $attributes->field();
$label = $attributes->get('label');
$caption = $attributes->get('caption');
@endphp

<div class="group inline-block">
    <label {{ $attributes->class([
        'inline-flex gap-3',
        'group-has-[.error]:border group-has-[.error]:border-red-500 group-has-[.error]:rounded-md group-has-[.error]:p-2'
    ])->only('class') }}>
        <input type="checkbox" class="hidden peer" {{ $attributes->except('class') }}>
    
        <div class="shrink-0 p-px border bg-white rounded border-gray-300 flex peer-checked:border-2 peer-checked:border-theme peer-checked:bg-theme peer-checked:ring-1 peer-checked:ring-theme peer-checked:ring-offset-1 peer-disabled:opacity-50 {{ $attributes->get('sm') ? 'w-4 h-4' : 'w-5 h-5' }}">
            <x-icon name="check" class="m-auto text-white text-xs"/>
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