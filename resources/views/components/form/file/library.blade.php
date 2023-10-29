@php
    $accept = $attributes->get('accept');
    $multiple = $attributes->get('multiple');
@endphp

<div 
    x-data="{
        page: 1,
        files: [],
        multiple: @js($multiple),
        checkboxes: [],
        show: false,
        loading: false,
        filters: { 
            mime: @js($accept), 
            search: null,
        },
        init () {
            this.$watch('showLibrary', (show) => show ? this.open() : this.close())
            this.$watch('show', (show) => showLibrary = show)
        },
        open () {
            this.show = true
            this.fetch()
        },
        close () {
            this.show = false
            this.reset()
        },
        reset () {
            this.page = 1
            this.files = []
            this.checkboxes = []
            this.loading = false
            this.filters.search = null
        },
        select (id) {
            const index = this.checkboxes.indexOf(id)
            if (index === -1) this.checkboxes.push(id)
            else this.checkboxes.splice(index, 1)

            if (!this.multiple) this.submit()
        },
        fetch () {
            if (this.page === -1) return
            if (this.loading) return

            this.files = this.page === 1 ? [] : this.files
            this.checkboxes = []
            this.loading = true

            axios.get(@js(route('__file.list')), { page: this.page, filters: this.filters })
                .then(res => {
                    this.page = res.data.length ? (this.page + 1) : -1
                    this.files = this.files.concat(res.data)
                    this.loading = false
                })
        },
        submit () {
            const value = this.checkboxes.map(id => (
                this.files.find(file => (file.id === id))
            ))

            this.$dispatch('files-selected', value)
            this.close()
        },
    }"
    x-show="show"
    x-transition.opacity
    x-on:files-uploaded="page = 1; fetch()"
    class="fixed inset-0"
    style="z-index: 999;">
    <x-modal.overlay x-on:click="close()"/>
    <div class="absolute inset-0 py-1 pl-2">
        <div class="bg-white border shadow-lg rounded-l-lg max-w-screen-lg h-full ml-auto flex flex-col">
            <div class="shrink-0 py-3 px-6 cursor-pointer border-b" x-on:click="close()">
                <x-icon name="arrow-left-long"/>
            </div>

            <x-heading title="file.heading.library" class="shrink-0 px-6 py-3 border-b"/>

            <div class="grow p-4 overflow-auto">
                <x-box>
                    <div class="flex flex-col">
                        <div x-show="checkboxes.length" class="py-2 px-4 flex items-center gap-3 justify-between border-b">
                            <div class="bg-gray-100 border border-gray-300 rounded-lg py-0.5 px-3 text-gray-500 inline-flex items-center gap-2">
                                <span x-text="`${checkboxes.length} selected`" class="text-sm font-medium"></span>
                                <div x-on:click="checkboxes = []" class="cursor-pointer flex">
                                    <x-icon name="xmark" class="m-auto"/>
                                </div>
                            </div>
        
                            <x-button.submit type="button" label="Select" x-on:click="submit"/>
                        </div>        

                        <div x-show="!checkboxes.length" class="p-3 border-b">
                            <div class="flex items-center divide-x divide-gray-300 border border-gray-300 rounded-md">
                                <div class="grow flex items-center gap-3 py-2 px-3">
                                    <div class="shrink-0">
                                        <x-icon name="search" class="text-gray-400"/>
                                    </div>
                                    <input 
                                        x-model="filters.search" 
                                        x-on:input.debounce.400ms="page = 1; fetch()"
                                        x-on:input.stop
                                        type="text" 
                                        class="transparent grow" 
                                        placeholder="{{ tr('common.label.search') }}">
                                    <x-close x-show="!empty(filters.search)" 
                                        x-on:click="filters.search = null; page = 1; fetch()"/>
                                </div>

                                <div class="shrink-0 px-4">
                                    <x-form.file.uploader :dropzone="false">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="upload"/> {{ tr('common.label.upload') }}
                                        </div>

                                        <x-slot:progress>
                                            <div class="font-semibold" x-text="progress+'%'"></div>
                                        </x-slot:progress>
                                    </x-form.file.uploader>
                                </div>
                            </div>
                        </div>
        
                        <template x-for="file in files">
                            <div
                                x-on:click="select(file.id)" 
                                x-bind:class="checkboxes.includes(file.id) ? 'bg-slate-100' : 'hover:bg-slate-50'"
                                class="py-2 px-4 flex items-center gap-3 border-b cursor-pointer">
                                <div x-show="checkboxes.includes(file.id)" class="shrink-0 flex text-green-500">
                                    <x-form.checkbox checked/>
                                </div>
                                
                                <figure class="shrink-0 w-10 h-10 rounded-md border overflow-hidden flex text-gray-400">
                                    <img x-show="file.is_image" x-bind:src="file.url" class="w-full h-full object-cover">
                                    <x-icon name="file" x-show="!file.is_image" class="m-auto"/>
                                </figure>

                                <div class="grow grid px-2">
                                    <div x-text="file.name" class="font-semibold truncate"></div>
                                    <div x-text="[file.type, file.size].filter(Boolean).join(' | ')" class="text-sm font-medium text-gray-500 md:hidden"></div>
                                </div>
        
                                <div x-text="file.size" class="shrink-0 truncate px-2 w-40 text-right text-gray-500 hidden md:block"></div>
                                <div x-text="file.mime" class="shrink-0 truncate px-2 w-40 text-right text-gray-500 hidden md:block"></div>
        
                            </div>
                        </template>

                        <div x-show="page === -1" class="py-2 px-4 text-center font-medium text-gray-500 text-sm">
                            - {{ tr('common.label.end-of-page') }} -
                        </div>

                        <div 
                            x-show="page !== -1 && !loading" 
                            x-on:click="fetch()"
                            class="p-4 font-medium flex items-center justify-center gap-3 cursor-pointer hover:bg-slate-100">
                            <x-icon name="arrow-rotate-right"/> {{ tr('common.label.load-more') }}
                        </div>

                        <div x-show="page !== -1 && loading" class="p-2 flex items-center gap-3 justify-center">
                            <div class="shrink-0 text-theme">
                                <x-spinner size="24"/>
                            </div>
                            <div class="font-medium">
                                {{ tr('common.label.loading') }}
                            </div>
                        </div>
                    </div>
                </x-box>
            </div>
        </div>
    </div>
</div>