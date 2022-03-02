<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Labels">
        <x-button icon="plus" href="{{ route('label.create', compact('type')) }}">
            New Label
        </x-button>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                @foreach ($types as $val)
                    <x-sidenav item href="{{ route('label.listing', [$val]) }}">{{ Str::headline($val) }}</x-sidenav>
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if (count($labels))
                <x-box>
                    <x-input.sortable wire:model="labels" :config="['handle' => '.sort-handle']" class="grid divide-y">
                        @foreach ($labels as $label)
                            <div class="flex">
                                <div class="flex-shrink-0 cursor-move sort-handle flex justify-center p-2 text-gray-400">
                                    <x-icon name="sort-alt-2"/>
                                </div>
                            
                                <a href="{{ route('label.update', [$label['id']]) }}" class="flex-grow py-2 px-4 hover:bg-gray-100">
                                    {{ $label['name'] }}
                                </a>
                            </div>
                        @endforeach
                    </x-input.sortable>
                </x-box>
            @else
                <x-box>
                    <x-empty-state/>
                </x-box>
            @endif
        </div>
    </div>
</div>