<div
    wire:ignore
    x-sort="() => $wire.sort($sortid)"
    class="flex flex-col divide-y">
    @foreach ($labels as $label)
        @php $count = $label->children()->count() @endphp
        <div
            x-sort:item
            data-id="{{ $label->id }}"
            class="flex flex-col divide-y rounded-sm hover:ring-1 hover:ring-theme hover:ring-offset-1">
            <div class="p-2 flex items-center gap-3">
                <div x-sort:handle class="shrink-0 px-2 cursor-move text-gray-400">
                    <x-icon name="sort"/>
                </div>

                <div class="grow flex items-center gap-3">
                    @if ($label->is_locked)
                        <div class="shrink-0 text-sm text-gray-400">
                            <x-icon name="lock"/>
                        </div>

                        @if ($label->color) <x-badge :badge="$label->badge()" :lower="false"/>
                        @else <div class="truncate font-medium">{{ $label->name_locale }}</div>
                        @endif
                    @else
                        <div wire:click.stop="$emit('editLabel', {{ Js::from(['id' => $label->id]) }})" class="w-full cursor-pointer">
                            @if ($label->color) <x-badge :badge="$label->badge()" :lower="false"/>
                            @else <div class="truncate font-medium">{{ $label->name_locale }}</div>
                            @endif
                        </div>
                    @endif

                    @if ($count)
                        <x-badge :label="$count" color="blue"/>
                    @endif
                </div>

                @if ($count)
                    <div class="shrink-0 px-2 cursor-pointer" x-on:click="setChildren(@js($label->id))">
                        <x-icon :name="get($children, 'parent_id') === $label->id ? 'chevron-down' : 'chevron-right'"/>
                    </div>
                @endif
            </div>

            @if (get($children, 'parent_id') === $label->id)
                <div x-on:click.stop class="px-2">
                    <livewire:app.label.listing
                        :labels="get($children, 'labels')"
                        :wire:key="'children-for-'.$label->id">
                    </livewire:app.label.listing>
                </div>
            @endif
        </div>
    @endforeach
</div>
