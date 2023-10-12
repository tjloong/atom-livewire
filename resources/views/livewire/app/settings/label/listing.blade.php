<x-sortable wire:ignore wire:sorted="sort" class="flex flex-col divide-y">
    @foreach ($labels as $label)
        <x-sortable.item :id="$label->id" class="p-2 flex items-center gap-3 hover:bg-slate-50" handle>
            @php $count = $label->children->count() @endphp
            <div 
                x-data="{ show: false }"
                x-on:click="show = !show"
                class="flex flex-col gap-2 {{ $count ? 'cursor-pointer' : null }}">
                <div class="flex items-center gap-3">
                    @if ($label->color)
                        <div class="shrink-0">
                            @if ($label->color_class)
                                <div class="w-5 h-5 rounded-full border shadow {{ $label->color_class }}"></div>
                            @else
                                <div class="w-5 h-5 rounded-full border shadow" style="background-color: {{ $label->color }}"></div>
                            @endif
                        </div>
                    @endif

                    <div class="grow flex items-center gap-3">
                        <x-link :label="$label->locale('name')" 
                            wire:click.stop="$emit('updateLabel', {{ $label->id }})"
                            class="font-medium"/>

                        @if ($count) <x-badge :label="$count" color="blue"/> @endif
                    </div>

                    @if ($count)
                        <div class="shrink-0">
                            <x-icon x-show="show" name="chevron-down" size="12"/>
                            <x-icon x-show="!show" name="chevron-right" size="12"/>
                        </div>
                    @endif
                </div>

                @if ($count)
                    <div 
                        x-show="show" 
                        x-on:click.stop 
                        x-on:sorted.stop
                        class="border rounded">
                        @livewire(
                            'app.settings.label.listing',
                            ['labels' => $label->children->sortBy('seq')],
                            key('children-for-'.$label->id),
                        )
                    </div>
                @endif
            </div>
        </x-sortable.item>
    @endforeach
</x-sortable>
