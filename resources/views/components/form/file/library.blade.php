@php
    $accept = $attributes->get('accept');
    $multiple = $attributes->get('multiple');
    $enableLibrary = $attributes->get('enable-library', false);
@endphp

@if ($enableLibrary)
    <x-drawer class="max-w-screen-xl">
        <x-slot:heading title="Files Library"></x-slot:heading>

        <div id="file-input-library" 
            x-data="{
                page: 1,
                text: null,
                files: [],
                multiple: @js($multiple),
                checkboxes: [],
                open () {
                    this.page = 1
                    this.text = null
                    this.files = []
                    this.checkboxes = []
                    this.fetch()
                    this.$dispatch('open')
                },
                search () {
                    this.page = 1
                    this.files = []
                    this.checkboxes = []
                    this.fetch()
                },
                select (file) {
                    const index = this.checkboxes.indexOf(file.id)
        
                    if (index > -1) this.checkboxes.splice(index, 1)
                    else this.checkboxes.push(file.id)
        
                    if (!this.multiple) this.submit()
                },
                fetch () {
                    return this.$wire.getFilesForLibrary({ 
                        filters: { 
                            mime: @js($accept),
                            search: this.text,
                        },
                        page: this.page,
                    }).then(res => {
                        if (res.length) {
                            this.files = this.files.concat(res)
                            this.page++
                        }
                        else this.page = null
                    })
                },
                submit () {
                    const value = this.checkboxes.map(id => (
                        this.files.find(file => (file.id === id))
                    ))
        
                    this.$dispatch('files-selected', value)
                    this.$dispatch('close')
                },
            }"
            x-on:open-library="open"
            class="flex flex-col divide-y">
            <div class="shrink-0 p-4">
                <div x-show="checkboxes.length" class="flex items-center gap-3 justify-between">
                    <div class="bg-gray-100 border border-gray-300 rounded-lg py-0.5 px-3 text-gray-500 inline-flex items-center gap-2">
                        <span x-text="`${checkboxes.length} selected`" class="text-sm font-medium"></span>
                        <div x-on:click="checkboxes = []" class="cursor-pointer flex">
                            <x-icon name="xmark" class="m-auto"/>
                        </div>
                    </div>

                    <x-button.submit type="button" label="Select" x-on:click="submit"/>
                </div>

                <div x-show="!checkboxes.length" class="form-input flex items-center gap-3">
                    <x-icon name="search" class="text-gray-400"/>
                    <input 
                        x-model="text" 
                        x-on:input.debounce.400ms="search()"
                        x-on:input.stop
                        type="text" 
                        class="transparent grow" 
                        placeholder="{{ __('Search') }}">
                    <x-close x-show="!empty(text)" x-on:click="text = null; search()"/>
                </div>
            </div>

            <div x-ref="files" class="grow overflow-auto flex flex-col">
                <template x-for="file in files">
                    <div 
                        x-on:click="select(file)"
                        x-bind:class="checkboxes.includes(file.id) ? 'bg-slate-100' : null"
                        class="px-2 py-3 flex items-center cursor-pointer border-b last:border-b-0 hover:bg-slate-50">
                        <div class="shrink-0 px-2">
                            <div
                                x-bind:class="checkboxes.includes(file.id) ? 'border-theme border-2' : 'border-gray-300'" 
                                class="w-6 h-6 p-0.5 bg-white border shadow rounded">
                                <div x-show="checkboxes.includes(file.id)" class="w-full h-full bg-theme"></div>
                            </div>
                        </div>

                        <div class="shrink-0 px-2">
                            <figure x-show="file.is_image" class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                                <img x-bind:src="file.url" class="w-full h-full object-cover">
                            </figure>

                            <div x-show="!file.is_image" class="w-10 h-10 rounded-lg border flex text-gray-500 bg-white">
                                <x-icon name="file" class="m-auto"/>
                            </div>
                        </div>

                        <div class="grow grid px-2">
                            <div x-text="file.name" class="font-semibold truncate"></div>
                            <div x-text="[file.type, file.size].filter(Boolean).join(' | ')" class="text-sm font-medium text-gray-500 md:hidden"></div>
                        </div>

                        <div x-text="file.size" class="shrink-0 px-2 w-32 text-right text-gray-500 hidden md:block"></div>
                        <div x-text="file.type" class="shrink-0 px-2 w-32 text-right text-gray-500 hidden md:block"></div>
                    </div>
                </template>

                <div x-show="!checkboxes.length" class="p-3">
                    <x-button icon="refresh" block
                        x-show="page !== null" 
                        x-on:click="fetch().finally(() => $refs.files.scrollTop = $refs.files.scrollHeight)" 
                        label="More"/>

                    <div x-show="page === null" class="text-center text-sm font-medium text-gray-500">
                        {{ __('- End of page -') }}
                    </div>
                </div>
            </div>
        </div>
    </x-drawer>
@endif