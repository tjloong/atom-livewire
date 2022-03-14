<div x-cloak>
    <div
        x-cloak
        x-data="uploader(@entangle('multiple'))" 
        x-show="show" 
        x-transition.opacity 
        x-on:{{ $uid }}-open.window="open()"
        x-on:{{ $uid }}-completed.window="close()"
        class="modal"
    >
        <div class="modal-bg"></div>
        <div class="modal-container" x-on:click="close()">
            <div class="modal-content max-w-screen-sm p-5" x-on:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-bold">
                        {{ $title }}
                    </div>
    
                    <a x-on:click="close()" class="flex items-center justify-center text-gray-800">
                        <x-icon name="x"/>
                    </a>
                </div>
    
                <div class="flex flex-wrap items-center space-x-6 mb-4 -mt-2">
                    @foreach ($tabs as $tab)
                        <a 
                            class="py-1.5 {{ $currentTab === $tab['name']
                                ? 'text-theme font-semibold border-b-2 border-theme'
                                : 'text-gray-400 font-medium hover:text-gray-600'
                            }}"
                            wire:click="$set('currentTab', '{{ $tab['name'] }}')"
                        >
                            {{ $tab['label'] }}
                        </a>
                    @endforeach
                </div>
    
                <div>
                    @if ($currentTab === 'device')
                        <div x-data="uploaderDevice(@js($inputFileTypes), @js(config('atom.max_upload_size')))">
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
                    @endif
                </div>
            
                <div>
                    @if ($currentTab === 'image')
                        <div x-data="uploaderImage">
                            @if ($multiple)
                                <x-input.textarea
                                    x-model="text"
                                    x-on:input="parseUrls($event.target.value)"
                                    caption="Insert multiple images by separating lines"
                                >
                                    Image URL
                                </x-input.textarea>
                            @else
                                <x-input.text x-model="text" x-on:input="parseUrls($event.target.value)">
                                    Image URL
                                </x-input.text>
                            @endif
                    
                            <template x-if="urls.length">
                                <x-input.field>
                                    <x-slot name="label">Preview</x-slot>
                                
                                    <div class="flex flex-wrap items-center space-x-2">
                                        <template x-for="(url, index) in urls" x-bind:key="`${index}-${url.href}`">
                                            <div class="w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                                                <template x-if="url.valid">
                                                    <img class="w-full h-full object-cover" x-bind:src="url.href" x-on:error="url.valid = false">
                                                </template>
                                
                                                <template x-if="!url.valid">
                                                    <div class="w-full h-full flex justify-center items-center text-red-400">
                                                        <x-icon name="error-circle" size="64px"/>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </x-input.field>
                            </template>
                    
                            <x-button
                                x-on:click="$wire.set('urls', getUrls())"
                                x-show="getUrls().length"
                                wire:loading.class="loading"
                                icon="check"
                                color="green"
                            >
                                Save Image
                            </x-button>
                        </div>
                    @endif
                </div>
            
                <div>
                    @if ($currentTab === 'youtube')
                        <div x-data="uploaderYoutube">
                            @if ($multiple)
                                <x-input.textarea
                                    x-model="text"
                                    x-on:input="parseUrls()"
                                    caption="Insert multiple Youtube videos by separating lines"
                                >
                                    Youtube URL
                                </x-input.textarea>
                            @else
                                <x-input.text x-model="text" x-on:input="parseUrls()">
                                    Youtube URL
                                </x-input.text>
                            @endif
            
                            <template x-if="urls.length">
                                <x-input.field>
                                    <x-slot name="label">Preview</x-slot>
                                
                                    <div class="flex flex-wrap items-center space-x-2">
                                        <template x-for="(url, index) in urls" x-bind:key="`${index}-${url.vid}`">
                                            <div class="relative w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                                                <template x-if="url.valid">
                                                    <div class="absolute inset-0">
                                                        <img x-bind:src="url.tn" class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="bg-white w-4 h-4"></div>
                                                    </div>
                                                    <div class="absolute inset-0 flex justify-center items-center text-red-500">
                                                        <x-icon name="youtube" type="logo" size="32px"/>
                                                    </div>
                                                </template>
                                
                                                <template x-if="!url.valid">
                                                    <div class="w-full h-full flex justify-center items-center text-red-400">
                                                        <x-icon name="error-circle" size="64px"/>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </x-input.field>
                            </template>
                    
                            <x-button
                                x-on:click="$wire.set('urls', getUrls())"
                                x-show="getUrls().length"
                                wire:loading.class="loading"
                                icon="check"
                                color="green"
                            >
                                Save Video
                            </x-button>
                        </div>
                    @endif
                </div>
            
                <div>
                    @if ($currentTab === 'library')
                        <div x-data="uploaderLibrary">
                            <div class="relative mb-6">
                                <div class="text-gray-400 absolute top-0 left-0 bottom-0 flex items-center justify-center px-2">
                                    <x-icon name="search" size="18px"/>
                                </div>
                                <input type="text" class="form-input w-full px-10" placeholder="Search" x-model.debounce="search">
                                <a 
                                    class="text-gray-500 absolute top-0 right-0 bottom-0 flex items-center justify-center px-2"
                                    x-show="search"
                                    x-on:click.prevent="search = null"
                                >
                                    <x-icon name="x" size="18px"/>
                                </a>
                            </div>
                    
                            <template x-if="!files.length && !loading">
                                <x-empty-state/>
                            </template>
                    
                            <template x-if="files.length">
                                <div class="grid grid-cols-2 gap-4 mb-4 md:grid-cols-4">
                                    <template x-for="file in files" x-bind:key="file.id">
                                        <div
                                            class="rounded-md shadow overflow-hidden pt-[100%] relative cursor-pointer bg-gray-100"
                                            x-init="file.selected = false"
                                            x-data="{
                                                select (file) {
                                                    if (!multiple) files.filter(val => (val.id !== file.id)).forEach(val => (val.selected = false))
                                                    file.selected = !file.selected
                                                }
                                            }"
                                            x-on:click="select(file)"
                                        >
                                            <div class="absolute inset-0" x-show="file.is_image">
                                                <img x-bind:src="file.url" class="w-full h-full object-cover">
                                            </div>
    
                                            <div class="absolute inset-0" x-show="file.is_video">
                                                <video class="w-full h-full object-cover">
                                                    <source x-bind:src="file.url"/>
                                                </video>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="w-8 h-8 bg-blue-500 rounded-full text-white flex items-center justify-center">
                                                        <x-icon name="play" size="28px"/>
                                                    </div>
                                                </div>
                                            </div>
                    
                                            <div class="absolute inset-0 flex items-center justify-center" x-show="file.type === 'youtube'">
                                                <template x-if="file.data?.vid">
                                                    <img x-bind:src="`https://img.youtube.com/vi/${file.data.vid}/default.jpg`" class="w-full h-full object-cover">
                                                </template>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="w-4 h-4 bg-white"></div>
                                                </div>
                                                <div class="absolute inset-0 flex items-center justify-center text-red-500">
                                                    <x-icon name="youtube" type="logo" size="32px"/>
                                                </div>
                                            </div>
                    
                                            <div class="absolute inset-0 flex items-center justify-center" x-show="file.type === 'pdf'">
                                                <x-icon name="file-pdf" type="solid" size="64px"/>
                                            </div>
                    
                                            <div class="absolute inset-0 flex items-center justify-center" x-show="!file.is_image && !file.is_video && !['youtube', 'pdf'].includes(file.type)">
                                                <x-icon name="file" type="solid" size="64px"/>
                                            </div>
                    
                                            <div class="absolute inset-0 flex items-center justify-center text-green-500" x-show="file.selected">
                                                <div class="absolute inset-0 bg-black opacity-50"></div>
                                                <div class="relative">
                                                    <x-icon name="check-circle" type="solid" size="32px"/>
                                                </div>
                                            </div>
                    
                                            <div class="absolute bottom-0 left-0 right-0 px-2 pb-2 pt-4 text-white bg-gradient-to-t from-black to-transparent opacity-80 overflow-hidden">
                                                <div class="truncate text-sm" x-text="file.name"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                    
                            <template x-if="loading">
                                <div class="flex items-center justify-center py-4">
                                    <x-loader class="text-gray-400"/>
                                    <div class="font-medium text-gray-600">
                                        Loading
                                    </div>
                                </div>
                            </template>
                    
                            <template x-if="paginator?.current_page < paginator?.last_page && !loading">
                                <a 
                                    class="py-2 font-medium rounded-md flex justify-center items-center text-gray-800 border-2 border-gray-500"
                                    x-on:click.prevent="fetch(paginator.current_page + 1)"
                                >
                                    Load More
                                </a>
                            </template>
                    
                            <x-button color="green" icon="check" class="mt-4" x-on:click="submit()" wire:loading.class="loading" x-show="getSelected().length">
                                Select (<span x-text="getSelected().length"></span>)
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
