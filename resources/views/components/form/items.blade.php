<x-form.field {{ $attributes->only(['error', 'caption', 'required', 'label']) }}>
    <div class="bg-slate-100 rounded-lg flex flex-col divide-y">
        {{ $slot }}

        @isset($button)
            @php $icon = $button->attributes->get('icon') @endphp
            @php $label = $button->attributes->get('label') @endphp
            <a 
                class="p-4 flex items-center justify-center gap-2 text-sm"
                {{ $button->attributes->except(['icon', 'label']) }}
            >
                @if ($icon !== false) <x-icon :name="$icon ?? $label" size="12"/> @endif
                @if ($label) {{ __($label) }}
                @else {{ $button }}
                @endif
            </a>
        @endisset
    </div>
</x-form.field>
