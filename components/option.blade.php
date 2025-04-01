@aware(['variant', 'multiple'])

@php
$variant = $variant ?? 'native';
$option = $attributes->get('option');
$value = $attributes->get('value') ?? get($option, 'value');
$label = $attributes->get('label') ?? get($option, 'label');
$color = $attributes->get('color') ?? get($option, 'color');
$avatar = $attributes->has('avatar') ? $attributes->get('avatar') : get($option, 'avatar');
$caption = $attributes->get('caption') ?? get($option, 'caption');
$note = $attributes->get('note') ?? get($option, 'note');
$badge = $attributes->get('badge') ?? get($option, 'badge');
$badgeColor = $attributes->get('badge-color') ?? get($option, 'badge_color');
$tag = $attributes->get('tag') ?? get($option, 'tag');
$meta = $attributes->get('meta') ?? get($option, 'meta');
$html = $slot->toHTML();
$attrs = $attributes->except(['option', 'label', 'badge', 'badge-color']);
@endphp

@if ($variant === 'native')
    <option data-atom-option {{ $attrs }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            {{ t($label) }}
        @endif
    </option>
@else
    <li x-on:mouseover="moveTo($el)" x-on:mouseout="moveTo($el, false)" data-atom-option {{ $attrs->except('value') }}>
        <div
            @if (!$attributes->get('x-model'))
            x-data="{
                option: @js([
                    'value' => $value,
                    'label' => $label,
                    'caption' => $caption,
                    'avatar' => $avatar,
                    'color' => $color,
                    'badge' => $badge,
                    'badgeColor' => $badgeColor,
                    'note' => $note,
                    'tag' => $tag,
                    'meta' => $meta,
                    'html' => $html,
                ]),

                init () {
                    if (!this.option.html) {
                        let color = this.option.color ? `<div style='background-color: ${this.option.color}' class='shrink-0 w-3 h-3 rounded-full bg-zinc-100 flex items-center justify-center'></div>` : ''
                        this.option.html = `<div class='flex items-center gap-2'>${color}<span>${this.option.label}</span></div>`
                    }
                },
            }"
            @elseif ($attributes->get('x-model') !== 'option')
            x-data="{
                option: { ...{{ $attributes->get('x-model') }}},
            }"
            @endif
            x-on:click="select(option.value)"
            x-bind:data-option-body="Atom.json(option)"
            x-bind:data-option-selected="isSelected(option.value)"
            class="p-2 flex gap-3 cursor-default rounded-md data-[option-selected]:bg-zinc-800/5 [[data-option-focus]>&]:bg-zinc-800/5">
            <div class="shrink-0 w-6 h-6 flex items-center justify-center text-transparent [[data-option-selected]>&]:text-zinc-400">
                <atom:icon check/>
            </div>

            <div x-html="option.html" class="grow" data-option-content></div>
        </div>
    </li>
@endif