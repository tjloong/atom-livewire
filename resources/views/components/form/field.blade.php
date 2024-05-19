@php
    $field = $attributes->get('name') ?? $attributes->wire('model')->value() ?? null;
    $fieldname = $field ? last(explode('.', $field)) : null;
    $wirekey = $attributes->get('wire:key') ?? $attributes->wire('model')->value();
    $err = $attributes->get('error');
    $tags = $attributes->get('tag') ?? $attributes->get('tags') ?? [];
    $label = $label ?? $attributes->get('label');
    $nolabel = $attributes->get('no-label', false) || $label === false;
    $required = $attributes->get('required') || (get($this, 'form.required', [])[$field] ?? false);
@endphp

<div wire:key="{{ $wirekey }}" {{ $attributes->class(['w-full flex flex-col gap-1'])->only('class') }}>
    @if (!$nolabel)
        <label class="flex items-center gap-2 flex-wrap">
            <div class="font-medium leading-5 text-gray-400 text-sm uppercase">
                @if ($label instanceof \Illuminate\View\Component) {{ $label }}
                @elseif ($label) {!! tr($label) !!}
                @elseif ($fieldname && str($fieldname)->is('*_id')) {{ str($fieldname)->replaceLast('_id', '') }}
                @elseif ($fieldname) {{ $fieldname }}
                @endif
            </div>

            @if ($tags)
                <div class="flex items-center gap-2">
                @foreach ($tags as $key => $val)
                    <x-badge :label="$val" :color="is_string($key) ? $key : null"/>
                @endforeach
                </div>
            @endif

            @if ($required)
                <x-icon name="asterisk" class="text-red-400 text-sm"/>
            @endif
        </label>
    @endif

    <div>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @elseif ($value = $attributes->get('value'))
            @if ($href = $attributes->get('href'))
                <x-link :label="$value" :href="$href" :target="$attributes->get('target', '_self')"/>
            @else
                {!! $value !!}
            @endif
        @elseif ($href = $attributes->get('href'))
            <x-link :href="$href" :target="$attributes->get('target', '_self')"/>
        @elseif ($badge = $attributes->get('badge'))
            @if (is_string($badge)) <x-badge :label="$badge"/>
            @else
                @foreach ($badge as $key => $val)
                    <x-badge :label="$val" :color="$key"/>
                @endforeach
            @endif
        @elseif ($address = $attributes->get('address'))
            @if (is_string($address)) {{ $address }}
            @else
                <div>
                    @if ($name = data_get($address, 'name')) {{ $name }}<br> @endif
                    @if ($company = data_get($address, 'company')) {{ $company }}<br> @endif
                    @if ($address = data_get($address, 'address')) {{ $address }} @endif
                </div>
            @endif
        @endif
    </div>

    @if ($caption = $attributes->get('caption'))
        <div class="text-sm text-gray-700">
            {{ tr($caption) }}
        </div>
    @endif

    @if ($err || $errors->first($field))
        <div
            wire:key="{{ uniqid() }}" 
            x-init="$el.parentNode.querySelectorAll('.form-input:not(.transparent)').forEach(node => node.addClass('error'))"
            class="text-sm text-red-500 font-medium form-field-error">
            {{ tr($err ?: $errors->first($field)) }}
        </div>
    @else
        <div x-init="$el.parentNode.querySelectorAll('.form-input.error').forEach(node => node.removeClass('error'))" class="hidden"></div>
    @endif
</div>
