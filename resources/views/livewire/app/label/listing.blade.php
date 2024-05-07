<div
    wire:ignore
    x-sort="() => $wire.sort(
        Array.from($el.querySelectorAll(`[data-id]`)).map(el => (el.getAttribute('data-id')))
    )"
    class="flex flex-col divide-y">
    @foreach ($labels as $label)
        @php $count = $label->children()->count() @endphp
        <div
            x-sort:item
            data-id="{{ $label->id }}"
            class="flex flex-col divide-y rounded hover:ring-1 hover:ring-theme hover:ring-offset-1">
            <div class="p-2 flex items-center gap-3">
                <div x-sort:handle class="shrink-0 px-2 cursor-move text-gray-400">
                    <x-icon name="sort"/>
                </div>

                @if ($label->color)
                    <div class="shrink-0">
                        <div class="w-5 h-5 rounded-full border shadow" style="background-color: {{ color($label->color) }}"></div>
                    </div>
                @endif

                <div class="grow flex items-center gap-3 cursor-pointer" wire:click.stop="$emit('updateLabel', {{ $label->id }})">
                    <div class="truncate font-medium">{{ $label->locale('name') }}</div>
                    @if ($count) <x-badge :label="$count" color="blue"/> @endif
                </div>

                @if ($count)
                    <div class="shrink-0 px-2 cursor-pointer" x-on:click="setChildren(@js($label->id))">
                        <x-icon :name="data_get($children, 'parent_id') === $label->id
                            ? 'chevron-down' : 'chevron-right'"/>
                    </div>
                @endif
            </div>

            @if (data_get($children, 'parent_id') === $label->id)
                <div x-on:click.stop x-on:sorted.stop class="px-2">
                    @livewire(
                        'app.label.listing',
                        ['labels' => data_get($children, 'labels')],
                        key('children-for-'.$label->id),
                    )
                </div>
            @endif
        </div>
    @endforeach
</div>
