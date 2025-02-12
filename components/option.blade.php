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
$attrs = $attributes->except(['option', 'label', 'badge', 'badge-color']);
@endphp

@if ($variant === 'listbox' || $multiple)
    <li
        x-on:click="select({{ js($value) }})"
        x-on:mouseover="focus($el)"
        x-on:mouseout="blur($el)"
        class="p-2 my-1 first:mt-0 last:mb-0 flex gap-3 cursor-default rounded-md data-[option-focus]:bg-zinc-800/5 data-[option-selected]:bg-zinc-800/5"
        x-bind:data-option-selected="isSelected({{ js($value) }})"
        data-option-value="{{ $value }}"
        data-atom-option
        {{ $attrs }}>
        <div class="shrink-0 w-6 h-6 flex items-center justify-center text-transparent [[data-option-selected]>&]:text-zinc-400">
            <atom:icon check/>
        </div>
        <div class="grow" data-option-content>
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                <div class="flex gap-2">
                    @if ($avatar)
                        <div class="shrink-0" data-option-avatar>
                            <atom:avatar :avatar="$avatar" size="20">
                                @t($label)
                            </atom:avatar>
                        </div>
                    @endif

                    @if ($color)
                        <div class="shrink-0 flex items-center justify-center">
                            <div
                                class="size-4 rounded-md"
                                style="background-color: {{ $color }}"
                                data-option-color>
                            </div>
                        </div>
                    @endif

                    <div class="grow">
                        <div class="text-wrap" data-option-label>@t($label)</div>
                        @if ($caption)
                            <div class="text-sm text-muted truncate" data-option-caption>@t($caption)</div>
                        @endif
                    </div>

                    @if ($badge)
                        <div class="shrink-0" data-option-badge>
                            <atom:_badge :color="$badgeColor" size="xs">@t($badge)</atom:_badge>
                        </div>
                    @elseif ($note)
                        <div class="shrink-0 text-right text-sm" data-option-note>
                            @t($note)
                        </div>
                    @endif
                </div>
            @endif
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