<div class="max-w-screen-lg mx-auto">
    <x-box :header="$this->title">
        <x-slot:header-buttons>
            <x-button size="sm" color="gray"
                label="New"
                wire:click="open"
            />
        </x-slot:header-buttons>

        @if ($this->labels->count())
            <x-form.sortable
                wire:sorted="sort"
                :config="['handle' => '.label-sorter']"
                class="grid divide-y"
            >
                @foreach ($this->labels as $label)
                    @php $count = $label->children->count() @endphp
                    <div 
                        x-data="{ show: false }" 
                        data-sortable-id="{{ $label->id }}"
                    >
                        <div class="flex items-center gap-3 hover:bg-slate-100">
                            <div class="shrink-0 cursor-move p-2 label-sorter flex">
                                <x-icon name="sort" class="m-auto text-gray-400"/>
                            </div>
                            
                            <div class="py-2 px-4 grow">
                                <div class="flex items-center gap-3">
                                    <div class="grow">
                                        <a wire:click="open(@js($label->id))" class="text-black hover:text-blue-500">
                                            {{ $label->locale('name') }}
                                        </a>
                                    </div>

                                    @if ($sublabel)
                                        <div 
                                            x-on:click="show = !show"
                                            class="shrink-0 px-4 text-sm font-medium text-gray-500 flex items-center gap-2 cursor-pointer"
                                        >
                                            {{ __(':count '.str()->plural('sublabel', $count), ['count' => $count]) }}
                                            <x-icon name="chevron-down" size="12"/>
                                        </div>
                                    @endif

                                    <div class="shrink-0">
                                        <x-close.delete
                                            title="Delete Label"
                                            message="Are you sure to delete this label?{{ $count ? ' All sub-labels will be deleted as well!' : '' }}"
                                            :params="$label->id"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($sublabel)
                            <div x-show="show" class="bg-gray-100 m-2 rounded-lg grid divide-y">
                                @if ($count)
                                    <x-form.sortable 
                                        wire:sorted="sort" 
                                        :config="['handle' => '.sublabel-sorter']"
                                        class="grid divide-y"
                                    >
                                        @foreach ($label->children()->orderBy('seq')->get() as $sub)
                                            <div class="py-2 pl-10 pr-4 flex items-center" data-sortable-id="{{ $sub->id }}">
                                                <div class="cursor-move px-4 flex text-gray-400 sublabel-sorter">
                                                    <x-icon name="sort" size="12"/>
                                                </div>

                                                <div class="grow">
                                                    <a 
                                                        wire:click="open(@js($sub->id))" 
                                                        class="text-sm text-gray-600 hover:bg-slate-100 hover:text-blue-500"
                                                    >
                                                        {{ $sub->locale('name') }}
                                                    </a>
                                                </div>

                                                <x-close.delete
                                                    title="Delete Label"
                                                    message="Are you sure to delete this label?"
                                                    :params="$sub->id"
                                                />    
                                            </div>
                                        @endforeach
                                    </x-form.sortable>
                                @endif

                                <a 
                                    wire:click="open(null, @js($label->id))"
                                    class="py-2 px-4 text-center text-sm flex items-center justify-center gap-2"
                                >
                                    <x-icon name="add" size="12"/> {{ __('New Sub-label') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </x-form.sortable>
        @else
            <x-empty-state
                title="No Labels"
                subtitle="The labels list is empty."
            >
                <x-button color="gray"
                    label="New Label"
                    wire:click="open"
                />
            </x-empty-state>
        @endif
    </x-box>

    @livewire(lw('app.preferences.label-form-modal'))
</div>