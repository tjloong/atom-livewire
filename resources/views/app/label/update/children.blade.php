<div>
    <x-box>
        <x-slot:header>
            <div class="flex items-center justify-between gap-2">
                {{ __('Label Children') }}
                <x-button icon="plus" size="sm" color="gray" 
                    label="Add"
                    wire:click="create"
                />
            </div>
        </x-slot:header>

        @if ($label->children->count())
            <x-form.sortable
                wire:sorted="sortChildren"
                :config="['handle' => '.sort-handle']"
                class="grid divide-y"
            >
                @foreach ($label->children()->orderBy('seq')->get() as $child)
                    <div class="flex gap-2 px-2" data-sortable-id="{{ $child->id }}">
                        <div class="shrink-0 cursor-move sort-handle flex justify-center text-gray-400 py-2">
                            <x-icon name="sort-alt-2"/>
                        </div>
                    
                        <div class="grow self-center">
                            <a 
                                wire:click="edit({{ $child->id }})"
                                class="flex-grow py-2 px-4 hover:bg-gray-100"
                            >
                                {{ $child->name }}
                            </a>
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

    <x-modal uid="child-form-modal" :header="(data_get($form, 'id') ? 'Update' : 'Create').' Label Child'">
        <form wire:submit.prevent="submit" class="grid gap-4">
            <x-form.text 
                label="Child Label Name"
                wire:model.defer="form.name" 
                :error="$errors->first('form.name')" 
                required
            />
    
            <x-form.slug 
                label="Child Label Slug"
                wire:model.defer="form.slug" 
                prefix="/"
                caption="Leave empty to auto generate"
            />

            <x-button.submit/>
        </form>
    </x-modal>
</div>