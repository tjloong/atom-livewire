<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Labels">
        <x-button.create label="New Label" href="{{ route('app.label.create', compact('type')) }}"/>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="type">
                @foreach ($this->types as $val)
                    <x-sidenav.item :name="$val">
                        {{ str()->headline($val) }}
                    </x-sidenav.item>
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($this->labels->count())
                <x-box>
                    <x-form.sortable
                        wire:sorted="sortLabels"
                        :config="['handle' => '.sort-handle']"
                        class="grid divide-y"
                    >
                        @foreach ($this->labels as $label)
                            <div class="flex gap-2 px-2" data-sortable-id="{{ $label->id }}">
                                <div class="shrink-0 cursor-move sort-handle flex justify-center text-gray-400 py-2">
                                    <x-icon name="sort-alt-2"/>
                                </div>
                            
                                <div class="grow self-center">
                                    <a 
                                        href="{{ route('app.label.update', [$label->id]) }}" 
                                        class="flex-grow py-2 px-4 hover:bg-gray-100"
                                    >
                                        {{ $label->name }}
                                    </a>
                                </div>

                                @if ($label->children_count)
                                    <div class="text-sm font-medium text-gray-500 self-center">
                                        {{ $label->children_count }} {{ str()->plural('child', $label->children_count) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </x-form.sortable>
                </x-box>
            @else
                <x-box><x-empty-state/></x-box>
            @endif
        </div>
    </div>
</div>