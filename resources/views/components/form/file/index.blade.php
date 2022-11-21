@props([
    'multiple' => $attributes->get('multiple', false),
    'accept' => $attributes->get('accept'),
    'visibility' => $attributes->get('visibility', 'public'),
    'webImage' => $attributes->get('web-image', true),
    'youtube' => $attributes->get('youtube', false),
    'library' => $attributes->get('library', true),
    'uid' => $attributes->get('uid', 'file-input'),
])

<div
    x-data="{
        value: @entangle($attributes->wire('model')),
        files: [],
        multiple: @js($multiple),
        get showDropzone () {
            return this.multiple || (!this.multiple && empty(this.value))
        },
        init () {
            this.getFiles()
            this.$watch('value', () => this.getFiles())
        },
        getFiles () {
            if (empty(this.value)) return
            this.$wire.getFiles(this.value).then(res => this.files = res)
        },
        preview (file) {
            if (file.is_image) this.$dispatch(@js($uid.'-preview-open'), file.url)
            else window.open(file.url, '_blank')
        },
        remove (fileId) {
            const index = this.files.findIndex(val => (val.id === fileId))
            this.files.splice(index, 1)

            if (this.multiple) this.value = this.files.map(val => (val.id))
            else this.value = null

            this.$dispatch('remove', fileId)
        },
        input (val) {
            if (empty(val)) return
            this.value = val
            this.$dispatch('input', val)
            this.$dispatch(@js($uid.'-library-close'))
        },
    }"
    x-on:uploaded="input($event.detail)"
    x-on:selected="input($event.detail)"
    x-on:add-urls="input($event.detail)"
    {{ $attributes->merge(['id' => $uid])->whereStartsWith(['id', 'x-', 'wire:']) }}
>
    <div x-bind:class="showDropzone && 'pb-4'">
        @isset($list)
            {{ $list }}
        @else
            <div x-show="files.length" class="flex flex-wrap gap-3">
                <template x-for="file in files">
                    <div 
                        x-on:click="preview(file)"
                        class="border shadow rounded-lg overflow-hidden max-w-sm cursor-pointer"
                    >
                        <div class="py-2 px-4 flex items-center gap-3">
                            <figure x-show="file.is_image" class="shrink-0 w-8 h-8">
                                <img x-bind:src="file.url" class="w-full h-full object-cover">
                            </figure>
                            
                            <x-icon x-show="!file.is_image" name="file" class="shrink-0" size="20"></x-icon>
    
                            <div class="text-sm font-medium grow grid">
                                <div x-text="file.name" class="truncate"></div>
                            </div>
    
                            <div class="shrink-0">
                                <x-close color="red" x-on:click.stop="remove(file.id)"/>
                            </div>
                        </div>
                    </div>
                </template>
    
                <x-form.file.preview :uid="$uid.'-preview'"/>
            </div>
        @endisset
    </div>

    <div 
        x-data="{
            tab: 'upload',
            select (tab) {
                if (tab === 'library') return this.$dispatch(@js($uid.'-library-open'))
                this.tab = tab
            }
        }"
        x-show="showDropzone"
        class="flex flex-col gap-2"
    >
        @php
            $tabs = array_filter([
                'upload',
                $webImage || $youtube ? 'url' : null,
                $library ? 'library' : null,
            ])
        @endphp

        @if (count($tabs) > 1)
            <div class="flex items-center gap-2">
                @foreach ($tabs as $item)
                    <div 
                        x-on:click="select(@js($item))"
                        x-bind:class="{
                            'bg-gray-100 font-semibold shadow border-gray-300 text-gray-600': tab === @js($item),
                            'bg-white font-medium text-gray-400 cursor-pointer': tab !== @js($item),
                        }"
                        class="text-xs py-1 px-2 border rounded-lg flex items-center gap-1"
                    >
                        <x-icon :name="[
                            'upload' => 'upload',
                            'url' => 'at',
                            'library' => 'grip',
                        ][$item]" size="12"/>

                        {{ __(strtoupper($item)) }}
                    </div>
                @endforeach
            </div>
        @endif

        <div x-show="tab === 'upload'">
            <x-form.file.dropzone
                :uid="$uid.'-dropzone'"
                :multiple="$multiple"
                :accept="$accept"
                :visibility="$visibility"
            />
        </div>

        <div x-show="tab === 'url'">
            <x-form.file.url
                :uid="$uid.'-url'"
                :multiple="$multiple"
                :youtube="$youtube"
                :web-image="$webImage"
            />
        </div>
    </div>

    @if (in_array('library', $tabs))
        <x-form.file.library
            :uid="$uid.'-library'"
            :multiple="$multiple"
            :filters="[
                'type' => [
                    'image/*' => 'image',
                    'video/*' => 'video',
                    'audio/*' => 'audio',
                    'youtube' => 'youtube',
                ][$accept] ?? null,
            ]"
        />
    @endif
</div>
