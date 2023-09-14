@php
    $max = $attributes->get('max') ?? config('atom.max_upload_size', 10);
    $model = $attributes->wire('model')->value();
    $accept = $attributes->get('accept') ?? '';
    $multiple = $attributes->get('multiple', false);
    $path = $attributes->get('path', 'uploads');
    $visibility = $attributes->get('visibility', 'public');
    $enableUrl = $attributes->get('enable-url', false);
    $enableLibrary = $attributes->get('enable-library', false);
@endphp

<div
    x-data="{
        error: false,
        queue: [],
        progress: 0,
        init () {
            this.$wire.on('filesUploaded', (e) => this.uploaded(e))
        },
        upload (files) {
            this.validate(files)

            this.$wire.set('fileInputUploads.{{ $model }}.path', '{{ $path }}')
            this.$wire.set('fileInputUploads.{{ $model }}.visibility', '{{ $visibility }}')

            this.$wire.uploadMultiple(
                'fileInputUploads.{{ $model }}.files',
                this.queue.map(q => q.file),
                () => this.progress = 0,
                () => {},
                (event) => this.progress = event.detail.progress,
            )
        },
        uploaded (data) {
            const files = data['{{ $model }}']

            if (!empty(files)) {
                this.$dispatch('files-uploaded', files)
            }
        },
        validate (files) {
            this.queue = []

            Array.from(files).forEach(file => {
                const size = file.size/1024/1024
                const accept = @js($accept).split(',').map(val => (val.trim())).filter(Boolean)
    
                if (accept.length && accept.some((val) => {
                    if (val.endsWith('*')) return !file.type.startsWith(val.replace('*', ''))
                    else if (val.startsWith('*')) return !file.type.endsWith(val.replace('*', ''))
                    else return !val.includes(file.type)
                })) {
                    this.error = '{{ __('atom::form.file.unsupported') }}'
                }
                else if (size >= @js($max)) {
                    this.error = '{{ __('atom::form.file.max-size', ['max' => format_filesize($max, 'MB')]) }}'
                }
                else {
                    this.queue.push({
                        file,
                        src: file.type.startsWith('image') ? URL.createObjectURL(file) : null,
                    })
                }
            })
        },
    }"
    x-on:dropped="upload($event.detail)"
    {{ $attributes->whereStartsWith('x-') }}>
    <div x-show="progress > 0" class="p-4">
        <div class="w-full rounded-full h-4 bg-white border overflow-hidden">
            <div class="w-full h-full bg-blue-200" x-bind:style="{ width: `${progress}%` }"></div>
        </div>
    </div>

    <div x-show="progress <= 0" class="flex flex-col">
        <div class="p-4 flex flex-col gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <input type="file" accept="{{ $accept }}" class="hidden" {{ $multiple ? 'multiple' : null }}
                    x-ref="input"
                    x-on:change="upload($event.target.files)"
                    x-on:input.stop>

                <x-button x-on:click="$refs.input.click()" label="Browse Files" icon="search" sm/>
                <x-form.file.dropzone :max="$max" class="grow"/>
            </div>

            <div x-show="error" class="text-red-500 font-medium text-sm flex items-center gap-2">
                <x-icon name="circle-exclamation"/>
                <span x-text="error"></span>
            </div>
        </div>
    
        @if ($enableLibrary || $enableUrl)
            <div class="flex items-center divide-x divide-gray-300 text-sm px-2 pb-2">
                @if ($enableLibrary)
                    <x-link label="Browser Library" class="px-2" x-on:click="$dispatch('browse-library')"/>
                @endif
    
                @if ($enableUrl && (!$accept || str($accept)->is('*image/*') || str($accept)->is('*youtube*')))
                    <x-link label="Get from URL" class="px-2" x-on:click="$dispatch('enable-url')"/>
                @endif
            </div>
        @endif
    </div>
</div>
