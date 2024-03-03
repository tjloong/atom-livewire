@php
    $max = $attributes->get('max') ?? config('atom.max_upload_size', 10);
    $model = $attributes->wire('model')->value();
    $accept = $attributes->get('accept') ?? '';
    $multiple = $attributes->get('multiple', false);
    $dropzone = $attributes->get('dropzone', true);
    $path = $attributes->get('path', 'uploads');
    $visibility = $attributes->get('visibility', 'private');
@endphp

<div
    x-data="{
        progress: 0,
        upload (files) {
            if (this.validate(files)) {
                const formdata = new FormData()

                Array.from(files).forEach(file => {
                    formdata.append('files[]', file)
                    formdata.append('path', @js($path))
                    formdata.append('visibility', @js($visibility))
                })

                const config = { onUploadProgress: (progressEvent) => {
                    this.progress = Math.round((progressEvent.loaded * 100)/progressEvent.total)
                }}

                axios.post(@js(route('__file.upload')), formdata, config)
                    .then(res => {
                        this.$dispatch('files-uploaded', res.data)
                        this.$wire.emit('fileUploaded')
                    })
                    .then(() => this.progress = 0)
            }
        },
        validate (files) {
            // scan for unsupported file type
            if (Array.from(files).some(file => {
                const accept = @js($accept).split(',').map(val => (val.trim())).filter(Boolean)
                return accept.length && accept.some((val) => {
                    if (val.endsWith('*')) return !file.type.startsWith(val.replace('*', ''))
                    else if (val.startsWith('*')) return !file.type.endsWith(val.replace('*', ''))
                    else return !val.includes(file.type)
                })
            })) {
                this.$dispatch('alert', { message: @js(tr('file.alert.unsupported')), type: 'error' })
                return false
            }

            // scan for oversize file
            if (Array.from(files).some(file => {
                const size = file.size/1024/1024
                return size >= @js($max)
            })) {
                this.$dispatch('alert', { message: @js(tr('file.alert.max-size', ['max' => format_filesize($max, 'MB')])), type: 'error' })
                return false
            }

            return true
        },
    }"
    x-on:dropped="upload($event.detail)"
    {{ $attributes->merge(['class' => 'relative'])->only('class') }}
    {{ $attributes->whereStartsWith('x-') }}>
    <input type="file" accept="{{ $accept }}" class="hidden" {{ $multiple ? 'multiple' : null }}
        x-ref="input"
        x-on:change="upload($event.target.files)"
        x-on:input.stop>

    <div
        x-show="progress <= 0"
        x-on:click="$refs.input.click()" 
        class="inline-flex flex-wrap items-center gap-3 cursor-pointer">
        @if ($slot->isNotEmpty()) {{ $slot }}
        @else
            <x-button icon="upload" label="app.label.upload"/>

            @if ($dropzone)
                <x-form.file.dropzone :max="$max" class="grow"/>
            @endif
        @endif
    </div>

    <div x-show="progress > 0">
        @isset($progress) {{ $progress }}
        @else
            <div class="w-full rounded-full h-4 bg-white border overflow-hidden">
                <div class="w-full h-full bg-blue-200" x-bind:style="{ width: `${progress}%` }"></div>
            </div>
        @endisset
    </div>
</div>
