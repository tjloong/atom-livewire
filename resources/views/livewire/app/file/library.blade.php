<x-modal.drawer class="max-w-screen-lg">
    <x-slot:heading
        icon="folder-open"
        title="{!! get($config, 'title', 'app.label.files-library') !!}">
    </x-slot:heading>

    <div
        x-cloak
        x-data="{
            files: @entangle('files'),
            multiple: {{Js::from(get($config, 'multiple'))}},
            checkboxes: [],

            isSelected (id) {
                return this.checkboxes.findIndex(val => (val.id === id)) > -1
            },

            select (file) {
                if (this.multiple) {
                    let index = this.checkboxes.findIndex(val => (val.id === file.id))
                    if (index > -1) this.checkboxes.splice(index, 1)
                    else this.checkboxes.push(file)
                }
                else {
                    this.$dispatch('files-selected', [file])
                    close()
                }
            },
        }"
        class="flex flex-col">
        <div x-show="checkboxes.length" class="p-4 flex items-center gap-3 border-b">
            <div class="bg-gray-100 border border-gray-300 rounded-lg py-0.5 px-3 text-gray-500 inline-flex items-center gap-2">
                <span x-text="`${checkboxes.length} selected`" class="text-sm font-medium"></span>
                <div x-on:click="checkboxes = []" class="cursor-pointer flex">
                    <x-icon name="xmark" class="m-auto"/>
                </div>
            </div>
    
            <x-button.submit sm type="button" label="app.label.select" x-on:click="() => {
                $dispatch('files-selected', checkboxes)
                close()
            }"/>
        </div>
    
        <div x-show="!checkboxes.length" class="p-4 border-b">
            <div class="flex items-center divide-x divide-gray-300 border border-gray-300 rounded-md">
                <div class="grow flex items-center gap-3 py-2 px-3">
                    <div class="shrink-0 text-gray-400">
                        <x-icon name="search"/>
                    </div>
    
                    <input type="text" wire:model.debounce.500ms="filters.search"
                        class="transparent grow" 
                        placeholder="{{ tr('app.label.search') }}">
    
                    @if (get($filters, 'search'))
                        <x-close wire:click="$set('filters.search', null)"/>
                    @endif
                </div>
    
                <div class="shrink-0 px-4">
                    <x-form.file.uploader :dropzone="false" :accept="get($config, 'accept')" :multiple="get($config, 'multiple')">
                        <div class="flex items-center gap-2">
                            <x-icon name="upload"/> {{ tr('app.label.upload') }}
                        </div>
    
                        <x-slot:progress>
                            <div class="font-semibold" x-text="progress+'%'"></div>
                        </x-slot:progress>
                    </x-form.file.uploader>
                </div>
            </div>
        </div>

        <template x-if="files && files.length">
            <div class="flex flex-col divide-y">
                <template x-for="file in files" hidden>
                    <div
                        x-on:click="select(file)" 
                        x-bind:class="isSelected(file.id) ? 'bg-slate-100' : 'hover:bg-slate-50'"
                        class="py-2 px-4 flex items-center gap-3 border-b cursor-pointer">
                        <div x-show="isSelected(file.id)" class="shrink-0 flex text-green-500 pointer-events-none">
                            <x-form.checkbox checked/>
                        </div>

                        <figure class="shrink-0 w-10 h-10 rounded-md border overflow-hidden flex text-gray-400">
                            <template x-if="file.is_image">
                                <img x-bind:src="file.url" class="w-full h-full object-cover">
                            </template>

                            <template x-if="!file.is_image">
                                <x-icon name="file" class="m-auto"/>
                            </template>
                        </figure>

                        <div class="grow grid px-2">
                            <div x-text="file.name" class="font-semibold truncate"></div>
                            <div x-text="[file.type, file.size].filter(Boolean).join(' | ')" class="text-sm font-medium text-gray-500 md:hidden"></div>
                        </div>

                        <div x-text="file.size" class="shrink-0 truncate px-2 w-40 text-right text-gray-500 hidden md:block"></div>
                        <div x-text="file.mime" class="shrink-0 truncate px-2 w-40 text-right text-gray-500 hidden md:block"></div>
                    </div>
                </template>

                @if ($hasMorePages)
                    <x-anchor label="app.label.load-more" align="center" class="py-2"
                        x-on:click="$wire.loadMore().then(() => {
                            $nextTick(() => {
                                let body = $el.closest('.modal-drawer-body')
                                body.scrollTop = body.scrollHeight
                            })
                        })"/>
                @endif
            </div>
        </template>

        <template x-if="!files || !files.length">
            <x-no-result sm/>
        </template>
    </div>
</x-modal.drawer>
