@props([
    'id' => component_id($attributes, 'file-uploader'),
    'accept' => $attributes->get('accept'),
    'compress' => $attributes->get('compress', true),
    'max' => $attributes->get('max-upload-size') ?? config('atom.max_upload_size') ?? 10,
    'location' => $attributes->get('location', 'uploads'),
    'visibility' => $attributes->get('visibility', 'public'),
])

<div 
    x-data="{
        queue: [],
        read (files) {
            Array.from(files).forEach(file => {
                if (this.validate(file)) {
                    this.queue.push({
                        file,
                        progress: 50,
                        status: 'pending',
                        src: file.type.startsWith('image') ? URL.createObjectURL(file) : null,
                    })
                }
            })

            this.upload()
        },
        upload () {
            if (!this.queue.length) return

            this.$dispatch('uploading')
            this.$wire.set('upload.compress', @js($compress))
            this.$wire.set('upload.location', @js($location))
            this.$wire.set('upload.visibility', @js($visibility))

            this.queue.forEach(item => {
                if (item.status !== 'pending') return

                // wire upload will only get filename
                // once upload is saved to file model, event 'upload-completed' will trigger
                // event 'upload-completed' will have the file id
                this.$wire.upload(
                    'upload.file',
                    item.file,
                    (filename) => item.filename = filename,
                    () => item.status = 'error',
                    (event) => item.progress = event.detail.progress - 1
                )
            })
        },
        uploaded (data) {
            const index = this.queue.findIndex((val) => (val.filename === data.filename))
            if (index === -1) return

            this.queue[index].file = data.file
            this.queue[index].progress = 100
            this.queue[index].status = 'completed'
            
            if (this.queue.some(item => (item.status === 'pending'))) return
            else {
                this.$dispatch('uploaded', this.queue.map(item => (item.file)))
                this.queue = []
            }
        },
        validate (file) {
            const size = file.size/1024/1024
            const accept = (@js($accept) || '').split(',').map(val => (val.trim())).filter(Boolean)

            if (accept.length && accept.some((val) => {
                if (val.endsWith('*')) return !file.type.startsWith(val.replace('*', ''))
                else if (val.startsWith('*')) return !file.type.endsWith(val.replace('*', ''))
                else return !val.includes(file.type)
            })) {
                this.$dispatch('alert', {
                    title: 'Unsupported File Type',
                    message: 'The file that you are trying to upload is not supported.',
                    type: 'error',
                })

                return false
            }

            if (size >= @js($max)) {
                this.$dispatch('alert', {
                    title: 'Max File Size Exceeded',
                    message: @js(__('File must be :max or smaller.', ['max' => format_filesize($max, 'MB')])),
                    type: 'error',
                })

                return false
            }

            return true
        },
    }"
    x-show="queue.filter(item => (item.status === 'pending')).length" 
    x-on:upload="read($event.detail)"
    x-on:upload-completed.window="uploaded($event.detail)"
    class="flex flex-col divide-y max-h-[200px] overflow-auto"
    id="{{ $id }}"
>
    <template x-for="(item, i) in queue">
        <div class="py-2 px-4 flex items-center gap-2">
            <div class="shrink-0 flex items-center justify-center">
                <x-icon x-show="item.status === 'pending'" name="upload" class="text-gray-400"/>
                <x-icon x-show="item.status === 'completed'" name="circle-check" class="text-green-500"/>
                <x-icon x-show="item.status === 'error'" name="circle-exclamation" class="text-red-500"/>
            </div>

            <div x-text="item.file.name" class="grow text-sm font-medium truncate"></div>
            
            <div class="shrink-0 w-32 h-2 rounded-full overflow-hidden">
                <div 
                    x-bind:style="{ width: `${item.progress}%` }" 
                    x-bind:class="item.progress >= 100 ? 'bg-green-500' : 'bg-blue-500'"
                    class="h-full"
                ></div>
            </div>
        </div>
    </template>
</div>
