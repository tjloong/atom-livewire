@props([
    'max' => $attributes->get('max-upload-size') ?? config('atom.max_upload_size') ?? 10,
])

<div 
    x-data="{
        show: false,
        drop (files) {
            this.show = false
            this.$dispatch('dropped', files)
        },
    }"
    x-on:dragover.prevent="show = true"
    x-on:dragenter.prevent="show = true"
    x-on:dragleave.prevent="show = false"
    x-on:dragend.prevent="show = false"
    x-on:drop.prevent="drop($event.dataTransfer.files)"
    {{ $attributes->merge(['class' => 'font-medium text-gray-500'])->only('class') }}
>
    <div x-show="show" class="absolute inset-0 bg-white border-2 border-green-500 rounded-lg border-dashed text-green-500 text-lg font-medium flex flex-col items-center justify-center gap-3 md:flex-row">
        <x-icon name="upload" size="24"/> {{ __('Drop here to upload') }}
    </div>

    {{ __('Or drag & drop here to upload (Max :max)', ['max' => format_filesize($max, 'MB')]) }}
</div>
