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
                        wire:sort="sortLabels"
                        :config="['handle' => '.sort-handle']"
                        class="grid divide-y"
                    >
                        @foreach ($this->labels as $label)
                            <div class="flex" data-sortable-id="{{ $label->id }}">
                                <div class="shrink-0 cursor-move sort-handle flex justify-center p-2 text-gray-400">
                                    <x-icon name="sort-alt-2"/>
                                </div>
                            
                                <div class="self-center">
                                    <a 
                                        href="{{ route('app.label.update', [$label->id]) }}" 
                                        class="flex-grow py-2 px-4 hover:bg-gray-100"
                                    >
                                        {{ $label->name }}
                                    </a>
                                </div>
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