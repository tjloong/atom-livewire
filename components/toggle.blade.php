@php
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;
$attrs = $attributes->except(['label', 'caption', 'error', 'field']);
@endphp

<label class="group/toggle inline-block space-y-2" data-atom-toggle>
    <div class="inline-flex gap-3">
        <div class="shrink-0 pt-0.5">
            <input type="checkbox" class="hidden peer" {{ $attrs }}>

            <button
                type="button"
                class="group h-5 w-8 relative inline-flex items-center rounded-full transition bg-zinc-800/15 peer-disabled:bg-zinc-800/10 peer-checked:bg-primary-800 peer-disabled:peer-checked:bg-zinc-500 peer-checked:border-0 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-primary group-has-[.error]/toggle:ring-1 group-has-[.error]/toggle:ring-red-500"
                x-on:click.stop="$el.parentNode.querySelector('input').click()">
                <span class="size-3.5 rounded-full transition translate-x-[3px] bg-white group-has-[:disabled]:bg-white/90 group-has-[:checked]:translate-x-[13px] group-has-[:checked]:bg-white"></span>
            </button>
        </div>

        @if ($slot->isNotEmpty())
            <div class="grow">
                {{ $slot }}
            </div>
        @elseif ($label && $caption)
            <div class="grow">
                <div>{!! t($label) !!}</div>
                <atom:caption>{!! t($caption) !!}</atom:caption>
            </div>
        @elseif ($label)
            <div class="grow">
                {!! t($label) !!}
            </div>
        @endif
    </div>

    <atom:_error>@t($error)</atom:_error>
</label>
