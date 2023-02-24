<div class="max-w-screen-lg mx-auto">
    <x-box :header="$this->title">
        <x-slot:buttons>
            <x-button size="sm" color="gray"
                label="New"
                wire:click="open({ type: '{{ $type }}' })"
            />
        </x-slot:buttons>

        @if ($this->labels->count())
            <x-form.sortable
                wire:sorted="sort"
                :config="['handle' => '.cursor-move']"
                class="grid divide-y"
            >
                @foreach ($this->labels as $label)
                    <div x-data="{ show: false }" data-sortable-id="{{ $label->id }}">
                        <div class="py-2 px-4 flex items-center gap-3 hover:bg-slate-100">
                            <div class="shrink-0 cursor-move p-2 flex">
                                <x-icon name="sort" class="m-auto text-gray-400"/>
                            </div>
                                
                            <div class="grow flex items-center gap-3">
                                <a 
                                    wire:click="open(@js(['id' => $label->id]))"
                                    class="text-black grid"
                                >
                                    <div class="truncate">
                                        {{ $label->locale('name') }}
                                    </div>
                                </a>

                                @if ($maxDepth > 1)
                                    <a x-on:click="show = !show" class="shrink-0 text-gray-800 px-2 flex items-center gap-2">
                                        @if ($count = $label->children->count()) 
                                            <x-badge :label="$count" size="xs" color="blue"/> 
                                        @endif
                                        <x-icon x-show="!show" name="chevron-right" size="12"/>
                                        <x-icon x-show="show" name="chevron-down" size="12"/>
                                    </a>
                                @endif
                            </div>

                            <div class="shrink-0 flex items-center gap-2">
                                @if ($maxDepth > 1)
                                    <x-button size="xs" color="gray"
                                        :label="$this->addSublabelButtonName ?? 'Add Sub-label'"
                                        wire:click="open({ parent_id: {{ $label->id }} })"
                                    />
                                @endif

                                <x-close.delete
                                    title="Delete Label"
                                    :message="collect([
                                        'Are you sure to delete this label?',
                                        $label->children->count() ? 'All sub-labels will be deleted as well!' : null,
                                    ])->filter()->join(' ')"
                                    :params="$label->id"
                                />
                            </div>
                        </div>

                        @if ($maxDepth > 1 && $label->children->count())
                            <div x-show="show" class="bg-gray-100 m-2 rounded-lg">
                                @livewire(lw('app.label.children'), [
                                    'parent' => $label,
                                    'maxDepth' => $maxDepth,
                                ], key($label->id.'-'.$label->children->count()))
                            </div>
                        @endif
                    </div>
                @endforeach
            </x-form.sortable>
        @else
            <x-empty-state
                :title="'No '.str($this->title)->singular()->headline()"
                :subtitle="'You do not have any '.str($this->title)->singular()->headline()->lower()"
            />
        @endif
    </x-box>

    @livewire(lw('app.label.form-modal'), key('label-form-modal'))
</div>