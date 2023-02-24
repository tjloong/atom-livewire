<x-form.sortable
    wire:sorted="sort"
    :config="['handle' => '.cursor-move']"
    class="flex flex-col divide-y"
>
    @foreach ($this->children as $child)
        <div x-data="{ show: false }" class="flex flex-col divide-y" data-sortable-id="{{ $child->id }}">
            <div 
                class="py-2 px-4 flex items-center gap-3 hover:underline" 
                style="padding-left: {{ $this->padding }}rem;"
            >
                <div class="shrink-0 cursor-move flex">
                    <x-icon name="sort" class="text-gray-400 m-auto"/>
                </div>

                <div class="grow flex items-center gap-2">
                    <a wire:click="$emitUp('open', @js(['id' => $child->id]))" class="text-gray-800">
                        {{ $child->locale('name') }}
                    </a>

                    @if ($maxDepth > $depth)
                        <a x-on:click="show = !show" class="shrink-0 text-gray-800 px-2 flex items-center gap-2">
                            @if ($count = $child->children->count())
                                <x-badge :label="$count" size="xs" color="blue"/>
                            @endif
                            <x-icon x-show="!show" name="chevron-right" size="12"/>
                            <x-icon x-show="show" name="chevron-down" size="12"/>
                        </a>
                    @endif
                </div>

                <div class="shrink-0 flex items-center gap-2">
                    @if ($maxDepth > $depth)
                        <x-button size="xs" color="gray"
                            :label="$this->addSublabelButtonName ?? 'Add Sub-label'"
                            wire:click="$emitUp('open', { parent_id: {{ $child->id }} })"
                        />
                    @endif

                    <x-close.delete
                        title="Delete Label"
                        message="This will delete the label. Are you sure?"
                        :params="$child->id"
                    />
                </div>
            </div>

            @if ($child->children->count())
                <div x-show="show">
                    @livewire(lw('app.label.children'), [
                        'parent' => $child,
                        'depth' => $depth + 1,
                        'maxDepth' => $maxDepth,
                    ], key($child->id))
                </div>
            @endif
        </div>
    @endforeach
</x-form.sortable>