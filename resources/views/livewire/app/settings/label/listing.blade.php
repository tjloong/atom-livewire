<x-sortable wire:ignore wire:sorted="sort" class="flex flex-col divide-y">
    @foreach ($labels as $label)
        <x-sortable.item :id="$label->id" handle>
            @php $count = $label->children->count() @endphp
            <div x-data="{ show: false }" class="p-2 flex flex-col gap-2">
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

                    @if ($count)
                        <div x-on:click="show = !show" class="grow flex items-center cursor-pointer">
                            <div class="grow flex items-center gap-3">
                                <x-link :label="$label->locale('name')" 
                                    wire:click.stop="$emit('updateLabel', {{ $label->id }})"/>
                                <x-badge :label="$count" color="blue"/>
                            </div>

                            <div class="shrink-0 px-2">
                                <x-icon x-show="show" name="chevron-down"/>
                                <x-icon x-show="!show" name="chevron-right"/>
                            </div>
                        </div>
                    @else
                        <x-link :label="$label->locale('name')" class="grow cursor-pointer"
                            wire:click.stop="$emit('updateLabel', {{ $label->id }})"/>
                    @endif
                </div>

                @if ($count)
                    <div x-show="show" x-on:click.stop x-on:sorted.stop>
                        <x-box.flat>
                            @livewire(
                                'app.settings.label.listing',
                                ['labels' => $label->children->sortBy('seq')],
                                key('children-for-'.$label->id),
                            )
                        </x-box.flat>
                    </div>
                @endif
            </div>
        </x-sortable.item>
    @endforeach
</x-sortable>