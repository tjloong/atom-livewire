<div
    x-data="{
        progress: 0,
        loading: false,
        max: {{ $max }} * 1024 * 1024,
        multiple: @json($multiple),
        read (e) {
            const files = Array.from(e.target.files)
            const exceeded = files.find(file => file.size >= this.max)

            if (exceeded) this.$dispatch('alert', { message: 'Selected files must be less than {{ $max }}MB.', type: 'error' })
            else {
                this.loading = true

                if (this.multiple) {
                    @this.uploadMultiple(
                        '{{ $attributes->wire('model')->value() }}', 
                        files,
                        () => this.loading = false, 
                        () => this.$dispatch('alert', { message: 'Unable to upload files', type: 'error' }),
                        (event) => this.progress = event.detail.progress
                    )
                }
                else {
                    @this.upload(
                        '{{ $attributes->wire('model')->value() }}', 
                        files[0], 
                        () => this.loading = false, 
                        () => this.$dispatch('alert', { message: 'Unable to upload file', type: 'error' }),
                        (event) => this.progress = event.detail.progress
                    )
                }
            }
        },
    }"
>
    <input x-ref="fileinput" x-on:change="read" type="file" class="hidden">

    <x-button
        x-on:click="$refs.fileinput.click()"
        x-bind:disabled="loading"
        icon="upload"
        {{ $attributes->filter(fn($val, $key) => !str($key)->is('wire*')) }}
    >
        <span x-show="loading" x-text="`Uploading ${progress}%`"></span>
        <span x-show="!loading">{{ $slot->isNotEmpty() ? $slot : __('Choose File') }}</span>
    </x-button>
</div>
