<div>
    <div x-data="{
        multiple: @js($multiple),
        filetypes: @js($inputFileTypes), 
        max: {{ config('atom.max_upload_size') }},
        status: null,
        progress: 0,

        complete (status) {
            this.status = status
            this.$refs.input.value = ''
        },
        
        validate (files) {
            if (this.status === 'unsupported') return this.status = null
    
            const sum = Array.from(files).reduce((acc, file) => (file.size + acc), 0)
            const size = Math.round(sum/1024/1024, 2)
    
            if (size >= this.max) this.status = 'oversize'
            else this.upload(files)
        },
        
        upload (files) {
            this.status = 'uploading'
    
            const finishCallback = () => this.complete('completed')
            const failedCallback = () => this.complete('failed')
            const progressCallback = (event) => this.progress = event.detail.progress
    
            this.$wire.uploadMultiple('files', files, finishCallback, failedCallback, progressCallback)
        },
        
        scan (e) {
            this.status = 'scanning'
    
            const items = Array.from(e.dataTransfer.items).filter(item => item.kind === 'file')
            const unsupported = items.some(item => (!this.filetypes.includes(item.type)))
    
            if (unsupported) this.status = 'unsupported'
        },    
    }">
        <div
            x-show="status !== 'uploading'"
            x-on:click="$refs.input.click()"
            x-on:dragover.prevent="scan"
            x-on:dragenter.prevent="scan"
            x-on:dragleave.prevent="status = null"
            x-on:dragend.prevent="status = null"
            x-on:drop.prevent="validate($event.dataTransfer.files)"
            x-bind:class="{
                'border-red-500': status === 'unsupported',
                'border-green-500': status === 'scanning',
                'border-gray-500': status !== 'unsupported' && status !== 'scanning',
            }"
            class="h-64 border-4 border-dashed cursor-pointer rounded-md"
        >
            <div x-show="status === 'scanning'" class="flex items-center justify-center text-green-500 h-full text-base font-semibold">
                Drop here to upload
            </div>

            <div x-show="status === 'unsupported'" class="flex items-center justify-center text-red-500 h-full text-base font-semibold">
                File type is not supported
            </div>

            <div x-show="status !== 'unsupported' && status !== 'scanning'" class="flex flex-col items-center justify-center h-full">
                <x-icon name="cloud-upload" size="48px" class="text-gray-500"/>
                <div class="font-semibold text-base">
                    Add File
                </div>
                <div class="text-gray-500 font-medium">
                    Or drop file to upload
                </div>
            </div>
        </div>

        <div x-show="status === 'uploading'" class="p-10 flex items-center justify-center">
            <div class="py-2 px-4 w-full bg-gray-600 rounded-md drop-shadow relative overflow-hidden">
                <div class="absolute top-0 left-0 bottom-0 bg-green-500" x-bind:style="{ width: `${progress}%` }"></div>
                <div class="font-medium text-white text-center relative">
                    <span x-show="progress >= 100">Processing...</span>
                    <span x-show="progress < 100">Uploading...</template>
                </div>
            </div>
        </div>

        <input 
            type="file" 
            x-ref="input"
            x-bind:multiple="multiple"
            x-on:change="validate($event.target.files)"
            class="hidden"
            accept="{{ implode(',', $inputFileTypes) }}"
        >

        @if ($errors->any())
            <div class="mt-4">
                <x-alert type="error">
                    Upload failed.
                </x-alert>
            </div>
        @else
            <div x-show="status === 'oversize'" class="mt-4">
                <x-alert type="error">
                    Total file(s) size exceeded the <span x-text="max"></span>MB limit.
                </x-alert>
            </div>
        @endif
    </div>
</div>