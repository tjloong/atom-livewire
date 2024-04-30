@php
    $max = $attributes->get('max') ?? config('atom.max_upload_size', 10);
    $model = $attributes->wire('model')->value();
    $accept = $attributes->get('accept') ?? '';
    $multiple = $attributes->get('multiple', false);
    $dropzone = $attributes->get('dropzone', true);
    $paste = $attributes->get('paste', true);
    $path = $attributes->get('path', 'uploads');
    $visibility = $attributes->get('visibility', 'private');
@endphp

<div
    x-data="{
        loading: false,
        endpoint: @js(route('__file.upload')),

        upload (files) {
            if (this.validate(files)) {
                const formdata = new FormData()

                Array.from(files).forEach(file => {
                    formdata.append('files[]', file)
                    formdata.append('path', @js($path))
                    formdata.append('visibility', @js($visibility))
                })

                this.loading = true

                ajax(this.endpoint).post(formdata).then(res => {
                    this.$dispatch('files-uploaded', res)
                    this.$wire.emit('fileUploaded')
                }).finally(() => this.loading = false)
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
                this.$dispatch('alert', { message: @js(tr('app.alert.unsupported-upload-file-type')), type: 'error' })
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
    x-on:pasted="upload($event.detail)"
    {{ $attributes->merge(['class' => 'relative w-full'])->only('class') }}
    {{ $attributes->whereStartsWith('x-') }}>
    <input type="file" accept="{{ $accept }}" class="hidden" {{ $multiple ? 'multiple' : null }}
        x-ref="input"
        x-on:change="upload($event.target.files)"
        x-on:input.stop>

    <div
        x-show="!loading"
        x-on:click="$refs.input.click()" 
        class="inline-flex flex-wrap items-center gap-3 cursor-pointer">
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <x-button icon="upload" label="app.label.upload"/>
            @if ($dropzone) <x-form.file.dropzone :max="$max" class="grow"/> @endif
        @endif
    </div>

    <div x-show="loading" class="flex flex-col items-center justify-center gap-3">
        <x-icon name="upload" class="text-theme"/>
        <i class="uploader-loader mx-auto"></i>
    </div>
</div>

@pushOnce('scripts')
<style>
.uploader-loader {
	--color: gray;
	--size-mid: 6vmin;
	--size-dot: 1.5vmin;
	--size-bar: 0.4vmin;
	--size-square: 3vmin;
	
	display: block;
	position: relative;
	width: 50%;
	display: grid;
	place-items: center;
}
.uploader-loader::before,
.uploader-loader::after {
	content: '';
	box-sizing: border-box;
	position: absolute;
}
.uploader-loader::before {
	height: var(--size-bar);
	width: 6vmin;
	background-color: var(--color);
	animation: uploader-loader 0.8s cubic-bezier(0, 0, 0.03, 0.9) infinite;
}

@keyframes uploader-loader {
	0%, 44%, 88.1%, 100% {
		transform-origin: left;
	}
	
	0%, 100%, 88% {
		transform: scaleX(0);
	}
	
	44.1%, 88% {
		transform-origin: right;
	}
	
	33%, 44% {
		transform: scaleX(1);
	}
}
</style>
@endPushOnce
