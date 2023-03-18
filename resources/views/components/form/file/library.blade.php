@props([
    'header' => $attributes->get('header', 'Files and Media'),
    'multiple' => $attributes->get('multiple', false),
    'filters' => $attributes->get('filters', []),
])

<x-modal :header="$header" class="max-w-screen-lg">
    <div 
        id="file-library"
        x-data="{
            page: 1,
            files: [],
            multiple: @js($multiple),
            filters: @js($filters),
            loading: false,
            get selected () {
                return this.files.filter(file => (file.is_checked)).map(file => (file.id))
            },
            open () {
                this.page = 1
                this.files = []
                this.getFiles()
                this.$el.closest('#modal').dispatchEvent(new Event('open'))
            },
            getFiles () {
                this.loading = true
                this.$wire.getFiles({ filters: this.filters, page: this.page })
                    .then(res => {
                        if (res.length) {
                            this.files = this.files.concat(res)
                            this.page++
                        }
                        else this.page = null
                    })
                    .finally(() => {
                        this.loading = false
                        if (this.page > 1) this.$refs.files.scrollTop = this.$refs.files.scrollHeight
                    })
            },
            select (file) {
                this.files = this.files.map(val => {
                    if (val.id === file.id) val.is_checked = !val.is_checked
                    else if (!this.multiple) val.is_checked = false
                    return val
                })
            },
            submit () {
                const selected = this.selected.map(sel => (this.files.find(file => (file.id === sel))))
                const value = this.multiple ? selected : selected[0]

                this.files = this.files.map(file => ({ ...file, is_checked: false }))
        
                this.$dispatch('library', value)
                this.$el.closest('#modal').dispatchEvent(new Event('close'))
            }
        }"
        x-on:open="open"
        class="flex flex-col divide-y"
    >
        <div x-show="files.length" x-ref="files" class="p-5 max-h-[500px] overflow-auto">
            <div class="grid grid-cols-3 gap-4 md:grid-cols-6">
                <template x-for="file in files">
                    <div 
                        x-on:click="select(file)"
                        class="relative rounded-lg shadow overflow-hidden cursor-pointer pt-[100%] cursor-pointer"
                    >
                        <figure x-show="file.is_image" class="absolute inset-0">
                            <img x-bind:src="file.url" class="w-full h-full object-cover"/>
                        </figure>
    
                        <div x-show="!file.is_image" class="absolute inset-0 bg-gray-200 flex">
                            <x-icon name="file" size="30" class="m-auto"/>
                        </div>
    
                        <div class="absolute bottom-0 left-0 right-0 px-2 pb-2 pt-4 bg-gradient-to-t from-black to-transparent opacity-80 overflow-hidden">
                            <div x-text="file.name" class="truncate text-sm text-white font-medium"></div>
                        </div>
    
                        <div x-show="file.is_checked" class="absolute inset-0 bg-black/50 flex">
                            <x-icon name="circle-check" size="24" class="text-green-500 m-auto"/>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    
        <div 
            x-show="selected.length > 1" 
            x-text="`${selected.length} Selected`"
            class="px-4 py-2 text-sm font-medium text-gray-500"
        ></div>
    
        <div x-show="loading" class="flex items-center justify-center gap-2 py-4">
            <x-spinner size="20" class="text-theme"/> Loading...
        </div>
                
        <div 
            x-show="!loading && (selected.length || page !== null)" 
            class="flex items-center justify-between gap-3 p-4 bg-gray-100 rounded-b-lg border-t"
        >
            <div>
                <x-button.submit type="button" icon="check"
                    label="Select" 
                    x-show="selected.length"
                    x-on:click="submit"
                />
            </div>

            <div>
                <x-button color="gray"
                    label="Load More"
                    x-show="page !== null"
                    x-on:click="getFiles"
                />
            </div>
        </div>
    
        <div x-show="!loading && !files.length">
            <x-empty-state/>
        </div>
    </div>
</x-modal>