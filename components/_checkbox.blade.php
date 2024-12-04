@php
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$size = $attributes->get('size');
$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;
$attrs = $attributes->except(['label', 'caption', 'error', 'field']);
@endphp

<label class="group/checkbox inline-block space-y-2" data-atom-checkbox>
    <div class="inline-flex {{ $size === 'sm' ? 'gap-2' : 'gap-3' }}">
        <div class="shrink-0 pt-0.5">
            <input type="checkbox" class="hidden peer" {{ $attrs }}>

            <div
                tabindex="0"
                role="checkbox"
                class="{{ $size === 'sm' ? 'size-4' : 'size-5' }} bg-white rounded-md border border-zinc-300 shadow-sm flex items-center justify-center ring-offset-1 focus:outline-none focus:ring-1 focus:ring-primary peer-checked:bg-primary peer-checked:border-primary group-has-[.error]/checkbox:ring-1 group-has-[.error]/checkbox:ring-red-500">
                <atom:icon check size="13" class="text-zinc-300 group-has-[:checked]:text-white"/>
            </div>
        </div>

        @if ($slot->isNotEmpty())
            <div class="grow">
                {{ $slot }}
            </div>
        @elseif ($label && $caption)
            <div class="grow {{ $size === 'sm' ? 'text-sm' : '' }}">
                <div>{!! t($label) !!}</div>
                <atom:caption>{!! t($caption) !!}</atom:caption>
            </div>
        @elseif ($label)
            <div class="grow {{ $size === 'sm' ? 'text-sm' : '' }}">
                {!! t($label) !!}
            </div>
        @endif
    </div>

    <atom:_error>@t($error)</atom:_error>
</label>
