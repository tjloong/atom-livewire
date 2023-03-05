@props([
    'model' => $attributes->wire('model')->value(),
    'name' => $attributes->get('name'),
])

<div {{ $attributes->class(['flex flex-col gap-1'])->only('class') }}>
    @if (isset($label) || component_label($attributes))
        <label class="flex items-center gap-2 font-medium leading-5 text-gray-400 text-sm">
            <span>
                @if (isset($label)) {{ $label }}
                @else {{ str(component_label($attributes))->upper() }}
                @endif
            </span>

            @if ($tag = $attributes->get('tag'))
                <span class="bg-blue-100 text-blue-500 font-medium text-xs px-2 py-0.5 rounded-md">
                    {{ __($tag) }}
                </span>
            @endif

            @if ($attributes->has('required'))
                @if ($attributes->get('required')) <x-icon name="asterisk" size="10" class="text-red-400"/> @endif
            @elseif ($field = $name ?? $model ?? null)
                <div 
                    x-data="{ required: false }" 
                    x-init="required = ($wire.get('form.required') || [])[@js($name ?? $model)]" 
                    x-show="required" 
                    class="flex"
                >
                    <x-icon name="asterisk" size="10" class="text-red-400 m-auto"/>
                </div>
            @endif
        </label>
    @endif

    <div>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @elseif ($value = $attributes->get('value'))
            @if ($href = $attributes->get('href'))
                <a href="{!! $href !!}" target="{{ $attributes->get('target', '_self') }}">
                    {!! $value !!}
                </a>
            @else
                {!! $value !!}
            @endif
        @endif
    </div>

    @if ($caption = $attributes->get('caption'))
        <div class="text-sm text-gray-700">
            {{ __($caption) }}
        </div>
    @endif

    @if ($error = $errors->first($name ?? $model))
        <div class="text-sm text-red-500 font-medium">
            {{ __($error) }}
        </div>
    @endif
</div>
