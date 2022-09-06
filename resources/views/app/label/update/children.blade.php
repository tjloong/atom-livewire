<div>
    <x-box header="Label Children">
        <x-slot:header-buttons>
            <x-button icon="plus" size="sm" color="gray" 
                label="Add"
                wire:click="$emitTo('{{ lw('app.label.update.child-form-modal') }}', 'open')"
            />
        </x-slot:header-buttons>

        @if ($children->count())
            <x-form.sortable
                wire:sorted="sort"
                :config="['handle' => '.sort-handle']"
                class="grid divide-y"
            >
                @foreach ($children as $child)
                    <div class="flex gap-2 px-2" data-sortable-id="{{ $child->id }}">
                        <div class="shrink-0 cursor-move sort-handle flex justify-center text-gray-400 py-2">
                            <x-icon name="sort-alt-2"/>
                        </div>
                    
                        <div class="grow self-center">
                            <div class="py-2 px-4 hover:bg-gray-100">
                                <a wire:click="$emitTo('{{ lw('app.label.update.child-form-modal') }}', 'open', {{ $child->id }})">
                                    {{ $child->locale('name') }}
                                </a>

                                @if ($str = collect($child->name)->filter(fn($name) => $name !== $child->locale('name')))
                                    <div class="text-sm text-gray-500 font-medium">
                                        {{ $str->join(' | ') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0 self-center">
                            <x-button.delete size="xs" inverted
                                title="Delete Label Child"
                                message="Are you sure to delete this label child?"
                                :params="$child->id"                        
                            />
                        </div>
                    </div>
                @endforeach
            </x-form.sortable>
        @else
            <x-empty-state title="No label children" subtitle="This label does not have any children."/>
        @endif
    </x-box>

    @livewire(lw('app.label.update.child-form-modal'), [
        'parent' => $label,
        'locales' => $locales,
    ])
</div>