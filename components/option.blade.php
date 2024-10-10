@aware(['variant'])

@php
$value = $attributes->get('value');
$label = $attributes->get('label');
@endphp

@if ($variant === 'listbox')
    <li
        wire:key="option-{{ $value }}"
        x-on:click="select({{ js($value) }})"
        x-on:mouseover="focus($el)"
        x-on:mouseout="blur($el)"
        class="p-2 my-1 first:mt-0 last:mb-0 flex gap-3 cursor-default rounded-md data-[option-focus]:bg-zinc-800/5 data-[option-selected]:bg-zinc-800/5"
        x-bind:data-option-selected="isSelected({{ js($value) }})"
        data-option-value="{{ $value }}"
        data-option-label="{{ $label }}"
        data-atom-option>
        <div class="shrink-0 w-6 h-6 flex items-center justify-center text-transparent [[data-option-selected]>&]:text-zinc-400">
            <atom:icon check/>
        </div>
        <div class="grow" data-option-content>
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                {{ t($label) }}
            @endif
        </div>
    </li>
@else
    <option {{ $attributes->merge([
        'wire:key' => $value ? 'option-'.$value : null
    ]) }} data-atom-option>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            {{ t($label) }}
        @endif
    </option>
@endif