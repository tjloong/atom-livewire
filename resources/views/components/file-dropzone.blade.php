@php
$max = $attributes->get('max') ?? config('atom.max_upload_size');
$accept = $attributes->get('accept');
$multiple = $attributes->get('multiple', false);
@endphp

<div
    x-data="{
        hover: false,
        loading: false,
        progress: null,
        config: {
            max: @js($max),
            accept: @js($accept),
            multiple: @js($multiple),
        },

        init () {
            let parent = $root.parentNode

            parent.addEventListener('dragover', e => this.enter(e))
            parent.addEventListener('dragenter', e => this.enter(e))
            parent.addEventListener('dragleave', e => this.leave(e))
            parent.addEventListener('dragend', e => this.leave(e))
            parent.addEventListener('drop', e => {
                this.leave(e)
                this.drop(e)
            })
        },

        enter (e) {
            e.preventDefault()
            if (this.hover || this.loading) return
            $root.parentNode.addClass('file-dropzone')
            this.hover = true
        },

        leave (e) {
            e.preventDefault()
            if (!this.hover || this.loading) return
            $root.parentNode.removeClass('file-dropzone')
            this.hover = false
        },

        drop (e) {
            let files = e.dataTransfer.files

            if (this.loading) return
            if (!files.length) return

            this.loading = true

            Atom.upload(files, {
                ...this.config,
                progress: (val) => this.progress = val,
            })
                .then(res => {
                    this.$dispatch('input', res.id)
                    this.$dispatch('uploaded', res.files)
                    Livewire?.emit('uploaded', res.files)
                })
                .catch(({ message }) => $dispatch('alert', { title: tr('app.label.unable-to-upload'), message, type: 'error' }))
                .finally(() => this.loading = this.hover = false)
        },
    }"
    {{ $attributes->except(['max', 'multiple']) }}>
    <div
        x-show="hover"
        class="absolute inset-0 z-20 bg-white rounded-lg border-2 border-dashed border-green-300 text-green-500 flex flex-col gap-2 items-center justify-center text-center pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-14 h-14 opacity-50 animate-bounce" fill="currentColor"><path d="M473.66,210c-16.56-12.3-37.7-20.75-59.52-24-6.62-39.18-24.21-72.67-51.3-97.45C334.15,62.25,296.21,47.79,256,47.79c-35.35,0-68,11.08-94.37,32.05a149.61,149.61,0,0,0-45.32,60.49c-29.94,4.6-57.12,16.68-77.39,34.55C13.46,197.33,0,227.24,0,261.39c0,34.52,14.49,66,40.79,88.76,25.12,21.69,58.94,33.64,95.21,33.64H240V230.42l-48,48-22.63-22.63L256,169.17l86.63,86.62L320,278.42l-48-48V383.79H396c31.34,0,59.91-8.8,80.45-24.77,23.26-18.1,35.55-44,35.55-74.83C512,254.25,498.74,228.58,473.66,210Z"></path><rect x="240" y="383.79" width="32" height="80.41"></rect></svg>
        <div class="font-medium text-2xl">
            {{ tr('app.label.drop-to-upload') }}
        </div>
    </div>

    <div
        x-show="loading"
        class="absolute inset-0 z-20 bg-white rounded-lg border-2 border-dashed border-gray-300 flex flex-col gap-2 items-center justify-center text-center pointer-events-none">
        <div class="flex items-center gap-2 py-2 px-4 bg-black/70 rounded-md text-white shadow-md">
            <x-spinner size="24"/>
            <div class="font-medium">
                {{ tr('app.label.uploading') }} <span x-text="progress"></span>...
            </div>
        </div>
    </div>
</div>