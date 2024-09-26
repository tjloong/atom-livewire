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
        jobs: [],
        endpoint: @js(route('__file.upload')),

        get loading () {
            return (this.jobs || []).some(job => (!job.completed))
        },

        upload () {
            let job = this.jobs.find(job => (!job.completed))

            if (job) {
                let formdata = new FormData()            
                formdata.append('files[]', job.file)
                formdata.append('path', @js($path))
                formdata.append('visibility', @js($visibility))
    
                atom.ajax(this.endpoint).post(formdata).then(res => {
                    job.res = res
                    job.completed = true
                    this.upload()
                })
            }
            else {
                let res = this.jobs.pluck('res').flat()
                this.$dispatch('files-uploaded', res)
                this.$wire.emit('filesUploaded')
            }
        },

        queue (files) {
            if (!this.validate(files)) return
            this.jobs = Array.from(files).map(file => ({ file, completed: false }))
            this.upload()
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
    x-on:dropped="queue($event.detail)"
    x-on:pasted="queue($event.detail)"
    {{ $attributes->merge(['class' => 'relative w-full'])->only('class') }}
    {{ $attributes->whereStartsWith('x-') }}>
    <input type="file" accept="{{ $accept }}" class="hidden" {{ $multiple ? 'multiple' : null }}
        x-ref="input"
        x-on:change="queue($event.target.files)"
        x-on:input.stop>

    <div
        x-show="!loading"
        x-on:click="$refs.input.click()" 
        class="inline-flex flex-wrap gap-3 cursor-pointer">
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <div><x-button sm icon="upload" label="app.label.upload"/></div>
            @if ($dropzone) <x-form.file.dropzone :max="$max" class="grow"/> @endif
        @endif
    </div>

    <div x-show="loading" class="flex flex-col gap-1 text-sm">
        <div class="text-sm text-gray-500">{{ tr('app.label.processing') }}</div>

        <template x-for="job in jobs" hidden>
            <div class="flex items-center gap-3">
                <div x-text="job.file.name" class="grow truncate"></div>
                <div class="shrink-0 w-20">
                    <i x-show="!job.completed" class="uploader-loader mx-auto"></i>
                    <div x-show="job.completed" class="w-full h-1 rounded-full bg-green-500"></div>
                </div>
            </div>
        </template>
    </div>
</div>
