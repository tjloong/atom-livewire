@props([
    'id' => component_id($attributes, 'file-library'),
    'header' => $attributes->get('header', 'File Library'),
    'accept' => $attributes->get('accept'),
    'multiple' => $attributes->get('multiple', false),
    'upload' => $attributes->get('upload', true),
    'max' => $attributes->get('max-upload-size') ?? config('atom.max_upload_size') ?? 10,
    'visibility' => $attributes->get('visibility', 'public'),
    'location' => $attributes->get('location', 'uploads'),
])

<div x-cloak
    x-data="{
        url: false,
        show: false,
        page: 1,
        text: null,
        files: [],
        queue: [],
        checkboxes: [],
        uploading: false,
        config: {
            multiple: @js($multiple),
            upload: @js($upload),
        },
        open () {
            this.show = true
            this.$nextTick(() => this.fetch())
        },
        close () {
            this.show = false
            this.reset()
        },
        fetch () {            
            return this.$wire.getFiles({ 
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

            if (!this.config.multiple) this.submit()
        },
        upload (files) {
            document.querySelector(@js('#'.$id.'-uploader')).dispatchEvent(
                new CustomEvent('upload', { bubble: false, detail: files })
            )
        },
        reset () {
            this.page = 1
            this.text = null
            this.files = []
            this.checkboxes = []
            this.url = false
        },
        submit () {
            const value = this.checkboxes.map(id => (
                this.files.find(file => (file.id === id))
            ))

            this.$dispatch('input', this.config.multiple ? value : value[0])
            this.close()
        },
    }"
    x-on:open="open"
    x-on:close="close"
    x-on:uploading="uploading = true"
    x-on:uploaded="uploading = false; reset(); fetch()"
    x-on:keyup.escape.window="close"
    x-show="show"
    x-transition.opacity
    x-bind:class="show && 'fixed inset-0 z-40'"
    id="{{ $id }}"
>
    <div x-on:click="$dispatch('close')" class="absolute inset-0 bg-black/80"></div>
    <div class="absolute right-0 top-0 bottom-0 p-4 w-full md:w-10/12 md:p-0">
        <div class="w-full h-full flex flex-col divide-y p-1 bg-white rounded-xl md:rounded-none">
            <div class="shrink-0 pt-3 px-4 pb-4 flex items-center gap-3 justify-between">
                <div class="text-lg font-bold">
                    {{ __($header) }}
                </div>
                <x-close x-on:click="$dispatch('close')"/>
            </div>

            <div class="shrink-0 p-4">
                <div x-show="checkboxes.length" class="bg-gray-100 border border-gray-300 rounded-lg py-0.5 px-3 text-gray-500 inline-flex items-center gap-2">
                    <span x-text="`${checkboxes.length} selected`" class="text-sm font-medium"></span>
                    <div x-on:click="checkboxes = []" class="cursor-pointer flex">
                        <x-icon name="xmark" size="12" class="m-auto"/>
                    </div>
                </div>

                <div x-show="!checkboxes.length" class="form-input flex items-center gap-3">
                    <x-icon name="search" class="text-gray-400"/>
                    <input 
                        x-model="text" 
                        x-on:input.debounce.400ms="search()"
                        x-on:input.stop
                        type="text" 
                        class="form-input transparent grow" 
                        placeholder="{{ __('Search') }}"
                    >
                    <x-close x-show="!empty(text)" x-on:click="text = null; search()"/>
                </div>
            </div>

            <div x-ref="files" class="grow h-px overflow-y-auto">
                <template x-for="file in files">
                    <div 
                        x-on:click="select(file)"
                        x-bind:class="checkboxes.includes(file.id) ? 'bg-slate-100' : null"
                        class="py-2 px-4 flex items-center gap-4 border-b cursor-pointer hover:bg-slate-100"
                    >
                        <div 
                            x-bind:class="checkboxes.includes(file.id) ? 'text-green-500' : 'text-gray-400'"
                            class="shrink-0 flex"
                        >
                            <x-icon name="circle-check" class="m-auto"/>
                        </div>

                        <div class="shrink-0">
                            <figure x-show="file.is_image" class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                                <img x-bind:src="file.url" class="w-full h-full object-cover">
                            </figure>

                            <div x-show="!file.is_image" class="w-10 h-10 rounded-lg border flex text-gray-500 bg-white">
                                <x-icon name="file" class="m-auto"/>
                            </div>
                        </div>

                        <div class="grow grid">
                            <div x-text="file.name" class="font-semibold truncate"></div>
                            <div x-text="[file.type, file.size].filter(Boolean).join(' | ')" class="text-sm font-medium text-gray-500 md:hidden"></div>
                        </div>

                        <div x-text="file.size" class="shrink-0 w-32 text-right text-gray-500 hidden md:block"></div>
                        <div x-text="file.type" class="shrink-0 w-32 text-right text-gray-500 hidden md:block"></div>
                    </div>
                </template>
            </div>

            
            @if ($upload)
                <x-form.file.uploader :id="$id.'-uploader'"
                    :accept="$accept"
                    :location="$location"
                    :visibility="$visibility"
                />

                <div x-show="url" x-on:input.stop="reset(); fetch()" class="shrink-0 bg-slate-100 p-4">
                    <div class="flex justify-end">
                        <x-close x-on:click="url = false"/>
                    </div>

                    <x-form.file.url :id="$id.'-url'"/>
                </div>
            @endif

            <div x-show="!uploading && !url" class="shrink-0 p-4 bg-gray-100 flex items-center gap-3 flex-wrap">
                <div class="grow flex items-center gap-2">
                    <x-button x-show="checkboxes.length" x-on:click="submit" label="Select" icon="check" color="gray" outlined/>

                    @if ($upload)
                        <div x-show="!checkboxes.length">
                            <input x-ref="input" 
                                x-on:change="upload($event.target.files)" 
                                x-on:input.stop type="file" 
                                class="hidden" 
                                accept="{{ $accept }}" 
                                multiple
                            >

                            <x-button x-on:click="$refs.input.click()" label="Upload" color="gray" outlined/>
                        </div>

                        <x-button x-show="!checkboxes.length" x-on:click="url = true" label="From URL" icon="code" color="gray" outlined/>
                    @endif
                </div>

                <div class="shrink-0">
                    <x-button color="gray" icon="refresh" outlined
                        x-show="!checkboxes.length && page !== null" 
                        x-on:click="fetch().finally(() => $refs.files.scrollTop = $refs.files.scrollHeight)" 
                        label="More"
                    />
                </div>
            </div>
        </div>
    </div>
</div>