@props([
    'config' => [
        'max' => $attributes->get('max') ?? config('atom.max_upload_size') ?? 10,
        'multiple' => $attributes->get('multiple', false),
        'accept' => $attributes->get('accept'),
        'visibility' => $attributes->get('visibility', 'public'),
        'wire' => $attributes->wire('model')->value(),
    ],
    'uid' => $attributes->get('uid', 'file-input'),
])

<div
    x-cloak
    x-data="{
        bucket: [],
        loading: false,
        config: @js($config),
        dropzone: 'idle',
        open () {
            if (!this.config.multiple && this.bucket.length) return
            this.$refs.fileinput.click()
        },
        read (files) {
            files = this.config.multiple ? Array.from(files) : [files[0]]

            files.forEach(file => this.bucket.push({
                file,
                progress: 0,
                error: false,
                src: file.type.startsWith('image') ? URL.createObjectURL(file) : null,
            }))

            this.dropzone = 'idle'

            this.bucket.forEach(item => {
                this.validate(item)
                this.upload(item)
            })
        },
        scan (e) {
            if (this.bucket.length) return

            this.dropzone = 'ok'

            const accept = (this.config.accept || '').split(',').map(val => (val.trim())).filter(Boolean)
            const items = Array.from(e.dataTransfer.items).filter(item => item.kind === 'file')

            if (accept.length && items.some(item => (!accept.includes(item.type)))) {
                this.dropzone = 'error'
            }
        },
        remove (i) {
            this.bucket.splice(i, 1)
        },
        validate (item) {
            const size = item.file.size/1024/1024

            if (size >= this.config.max) {
                item.error = @js(__('File must be :max or smaller.', [
                    'max' => format_filesize(data_get($config, 'max'), 'MB'),
                ]))
            }
        },
        upload (item) {
            if (item.error || item.completed) return

            const config = {
                headers: { 'Content-Type': 'multipart/form-data' },
                onUploadProgress: (e) => item.progress = Math.round((e.loaded * 100) / e.total),
            }

            const formData = new FormData()
            formData.append('upload', item.file)
            formData.append('visibility', this.config.visibility)

            axios.post(@js(route('__file.upload')), formData, config)
                .then(res => item.res = res.data)
                .catch(err => item.error = err.message)
                .finally(() => {
                    item.completed = true
                    this.input()
                })
        },
        input () {
            if (this.bucket.some(item => (!item.completed))) return

            let value = this.bucket.map(val => val.res?.id).filter(Boolean)
            if (!this.config.multiple) value = value[0]

            if (this.config.wire) this.$wire.set(this.config.wire, value)

            this.$dispatch('uploaded', value)
            this.bucket = []
            this.$dispatch('toast', { message: @js(__('Upload Completed')) })
        },
    }"
    {{ $attributes->merge(['id' => $uid])->whereStartsWith(['id', 'x-', 'wire:uploaded']) }}
>
    <input 
        x-ref="fileinput" 
        x-on:change="read($event.target.files)" 
        type="file" 
        class="hidden"
        {{ $attributes->only(['accept', 'multiple']) }}
    >

    @if ($slot->isNotEmpty())
        <div x-on:click="open" class="cursor-pointer">
            {{ $slot }}
        </div>
    @else
        <div 
            x-on:dragover.prevent="scan"
            x-on:dragenter.prevent="scan"
            x-on:dragleave.prevent="dropzone = 'idle'"
            x-on:dragend.prevent="dropzone = 'idle'"
            x-on:drop.prevent="read($event.dataTransfer.files)"
            x-bind:class="{
                'border-green-500': dropzone === 'ok',
                'border-red-500': dropzone === 'error',
                'border-gray-300': dropzone === 'idle',
            }"
            {{ $attributes->class([
                'relative w-full h-full border-4 border-dashed rounded-xl',
                'flex items-center justify-center',
                $attributes->get('class'),
            ])->only('class') }}
        >
            <div x-show="bucket.length" class="grid gap-1 w-full p-4">
                <template x-for="(item, i) in bucket">
                    <div class="border shadow rounded-lg overflow-hidden">
                        <div class="py-2 px-4 flex items-center gap-3">
                            <figure x-show="item.src" class="shrink-0 w-8 h-8">
                                <img x-bind:src="item.src" class="w-full h-full object-cover">
                            </figure>
                            
                            <x-icon x-show="!item.src" name="file" class="shrink-0" size="20"></x-icon>

                            <div class="text-sm font-medium grow grid">
                                <div x-text="item.file.name" class="truncate"></div>
                                <div x-show="item.error" class="flex items-center gap-1 text-red-500">
                                    <x-icon name="circle-info" size="10"/>
                                    <div x-text="item.error" class="text-xs"></div>
                                </div>
                                <div x-show="!item.error" x-text="formatFilesize(item.file.size)" class="text-xs text-gray-500"></div>
                            </div>

                            <div x-show="item.progress < 100" x-text="`${item.progress}%`" class="shrink-0 text-sm"></div>

                            <div x-show="item.progress >= 100" class="shrink-0 flex">
                                <x-icon name="circle-check" class="text-green-500 m-auto"></x-icon>
                            </div>

                            <div x-show="item.progress === 0" class="shrink-0">
                                <x-close color="red" x-on:click.stop="remove(i)"/>
                            </div>
                        </div>

                        <div class="bg-gray-300">
                            <div x-bind:style="{ width: `${item.progress}%` }" class="bg-green-500 h-1"></div>
                        </div>
                    </div>
                </template>

                <div x-show="config.multiple" x-on:click="open" class="cursor-pointer flex items-center gap-2 mt-4">
                    <x-icon name="upload" class="text-gray-500"/> {{ __('Upload more') }}
                </div>
            </div>

            <div 
                x-show="!bucket.length" 
                x-on:click="open" 
                class="flex flex-col items-center justify-center p-6 cursor-pointer"
            >
                <div class="flex items-center justify-center gap-2">
                    <x-icon name="upload" class="text-gray-500" size="18"/> 
                    <div class="font-medium">{{ __('Browse') }}</div>
                </div>
                <div class="text-gray-500 font-medium text-center">
                    {{ __('Or drop '.(data_get($config, 'multiple') ? 'files' : 'file').' here to upload') }}
                </div>
                @if ($caption = $attributes->get('caption', 'File must be :max or smaller.'))
                    <div class="text-sm font-medium text-gray-400 text-center">
                        {{  __($caption, ['max' => format_filesize(data_get($config, 'max'), 'MB')]) }}
                    </div>
                @endif
            </div>

            <div x-show="dropzone === 'ok'" class="absolute inset-0 bg-green-100 flex rounded-xl">
                <div class="text-green-500 text-center font-medium m-auto">
                    {{ __('Drop here to upload') }}
                </div>
            </div>

            <div x-show="dropzone === 'error'" class="absolute inset-0 bg-red-100 flex rounded-xl">
                <div class="text-red-500 text-center font-medium m-auto">
                    {{ __('File type is not supported') }}
                </div>
            </div>
        </div>
    @endif
</div>
