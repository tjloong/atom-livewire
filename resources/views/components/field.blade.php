<div {{ $attributes->merge([
    'class' => 'py-2 px-4 flex flex-col gap-2 md:flex-row md:items-center hover:bg-slate-100',
])->only('class') }}>
    <div class="md:w-2/5">
        @isset($label) {{ $label }}
        @elseif ($label = $attributes->get('label'))
            <div class="font-medium text-gray-400 text-sm flex items-center gap-2">
                @if ($icon = $attributes->get('icon')) <x-icon :name="$icon"/> @endif
                {{ __(str()->upper($label)) }}
            </div>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="md:w-3/5">
            {{ $slot }}
        </div>
    @elseif ($badge = $attributes->get('badge'))
        <div class="md:w-3/5 md:text-right">
            @if (is_string($badge)) <x-badge :label="$badge"/>
            @elseif (is_array($badge)) <x-badge :label="data_get($badge, 'label')" :color="data_get($badge, 'color')"/>
            @endif
        </div>
    @elseif ($value = $attributes->get('value'))
        <div class="md:w-3/5 md:text-right">
            @if ($href = $attributes->get('href'))
                <a href="{!! $href !!}" target="{{ $attributes->get('target', '_self') }}">
                    {!! $value !!}
                </a>
            @else
                {!! $value !!}
            @endif
        </div>
    @endif        
</div>
