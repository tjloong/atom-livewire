@aware(['variant', 'multiple'])

@php
$option = $attributes->get('option');
$value = $attributes->get('value') ?? get($option, 'value');
$label = $attributes->get('label') ?? get($option, 'label');
$color = $attributes->get('color') ?? get($option, 'color');
$avatar = $attributes->has('avatar') ? $attributes->get('avatar') : get($option, 'avatar');
$caption = $attributes->get('caption') ?? get($option, 'caption');
$note = $attributes->get('note') ?? get($option, 'note');
$badge = $attributes->get('badge') ?? get($option, 'badge');
$badgeColor = $attributes->get('badge-color') ?? get($option, 'badge_color');
$meta = $attributes->get('meta') ?? get($option, 'meta');
$attrs = $attributes->except(['option', 'label', 'badge', 'badge-color']);
@endphp

@if ($variant === 'listbox' || $multiple)
    <li x-on:mouseover="moveTo($el)" x-on:mouseout="moveTo($el, false)" data-atom-option {{ $attrs->except('value') }}>
        <div
            @if ($attributes->get('x-model') === 'option')
            x-data="{
                optValue: option.value,
                optLabel: option.label,
                optCaption: option.caption,
                optAvatar: option.avatar,
                optColor: option.color,
                optBadge: option.badge,
                optBadgeColor: option.badge_color,
                optNote: option.note,
                optMeta: JSON.stringify(option.meta),
            }"
            @else
            x-data="{
                optValue: @js($value),
                optLabel: @js($label),
                optCaption: @js($caption),
                optAvatar: @js($avatar),
                optColor: @js($color),
                optBadge: @js($badge),
                optBadgeColor: @js($badgeColor),
                optNote: @js($note),
                optMeta: @js($meta),
            }"
            @endif
            x-on:click="select(optValue)"
            x-bind:data-option-value="optValue"
            x-bind:data-option-meta="optMeta"
            x-bind:data-option-selected="isSelected(@if ($value) {{ js($value) }} @else option.value @endif)"
            class="p-2 flex gap-3 cursor-default rounded-md data-[option-selected]:bg-zinc-800/5 [[data-option-focus]>&]:bg-zinc-800/5">
            <div class="shrink-0 w-6 h-6 flex items-center justify-center text-transparent [[data-option-selected]>&]:text-zinc-400">
                <atom:icon check/>
            </div>

            <div class="grow" data-option-content>
                <div class="flex gap-2">
                    {{-- <template x-if="optAvatar" hidden>
                        <div class="shrink-0" data-option-avatar>
                            <atom:avatar :avatar="$avatar" size="20">
                                @t($label)
                            </atom:avatar>
                        </div>
                    </template> --}}

                    <template x-if="optColor" hidden>
                        <div class="shrink-0 flex items-center justify-center">
                            <div x-bind:style="{ backgroundColor: optColor }" class="size-4 rounded-md" data-option-color></div>
                        </div>
                    </template>

                    <div class="grow">
                        <div x-text="optLabel" class="text-wrap" data-option-label></div>
                        <template x-if="optCaption" hidden>
                            <div x-text="optCaption" class="text-sm text-muted truncate" data-option-caption></div>
                        </template>
                    </div>

                    {{-- @if ($badge)
                        <div class="shrink-0" data-option-badge>
                            <atom:_badge :color="$badgeColor" size="xs">@t($badge)</atom:_badge>
                        </div>
                    @elseif ($note)
                        <div class="shrink-0 text-right text-sm" data-option-note>
                            @t($note)
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </li>
@else
    <option data-atom-option {{ $attrs }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            {{ t($label) }}
        @endif
    </option>
@endif