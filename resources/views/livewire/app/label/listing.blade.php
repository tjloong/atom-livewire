<div
    x-sort="() => $wire.sort($sortid)"
    class="flex flex-col divide-y">
    @foreach ($labels->sortBy('seq') as $label)
        <div
            x-sort:item
            data-id="{{ $label->id }}"
            class="p-2 flex items-center gap-3">
            <div x-sort:handle class="shrink-0 px-2 cursor-move text-gray-400">
                <x-icon name="sort"/>
            </div>

            <div
                wire:click.stop="$emit('editLabel', {{ Js::from(['id' => $label->id]) }})"
                class="grow flex items-center gap-3 cursor-pointer">
                @if ($label->is_locked)
                    <div class="shrink-0 text-sm text-gray-400">
                        <x-icon name="lock"/>
                    </div>

                    @if ($label->color) <x-badge :badge="$label->badge()" :lower="false"/>
                    @else <div class="truncate font-medium">{{ $label->name_locale }}</div>
                    @endif
                @elseif ($label->color)
                    <x-badge :badge="$label->badge()" :lower="false"/>
                @else
                    <div class="truncate font-medium">{{ $label->name_locale }}</div>
                @endif
            </div>

            @if ($count = $label->children()->count())
                <div class="shrink-0 px-2 text-gray-500 text-sm cursor-pointer">
                    {{ $count }} sub-label
                </div>
            @endif
        </div>
    @endforeach
</div>
