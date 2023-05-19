@props([
    'id' => component_id($attributes, 'file-input'),
    'model' => $attributes->wire('model')->value(),
    'value' => data_get($this, $attributes->wire('model')->value()),
    'accept' => $attributes->get('accept'),
    'multiple' => $attributes->get('multiple', false),
    'sortable' => $attributes->get('sortable', false),
    'library' => $attributes->get('library', true),
    'upload' => $attributes->get('upload', true),
    'visibility' => $attributes->get('visibility', 'public'),
    'location' => $attributes->get('location', 'uploads'),
])

<x-form.field {{ $attributes }}>
    <div x-data="{
        checkboxes: [],
        config: {
            model: @js($model),
            accept: @js($accept),
            multiple: @js($multiple),
            sortable: @js($sortable),
            upload: @js($upload),
        },
    }" class="relative border rounded-lg bg-slate-100 flex flex-col divide-y overflow-hidden" id="{{ $id }}">
        @if (
            $value
            && ($files = model('file')->whereIn('id', (array)$value)->get())
            && $files->count()
            && ($files = collect($value)->map(fn($val) => $files->firstWhere('id', $val)))
        )
            <div
                x-data="{
                    init () {
                        if (config.sortable) new Sortable(this.$refs.sortable, { onSort: () => this.sorted() })
                    },
                    select (id) {
                        const index = checkboxes.indexOf(id)
        
                        if (index > -1) checkboxes.splice(index, 1)
                        else checkboxes.push(id)
                    },
                    remove () {
                        if (config.model) {
                            if (config.multiple) {
                                let value = [...new Set(this.$wire.get(config.model) || [])]
                                checkboxes.forEach(id => value.splice(value.indexOf(id), 1))
                                this.$wire.set(config.model, value)
                            }
                            else this.$wire.set(config.model, null)
                        }
                        
                        this.$dispatch('remove', checkboxes)
                        checkboxes = []
                    },
                    sorted () {
                        const data = Array.from(this.$refs.sortable.children).map(child => (child.getAttribute('data-sortable-id')))
                        this.$dispatch('sorted', data)
                    },
                    preview (url) {
                        $el.querySelector(@js('#'.$id.'-preview')).dispatchEvent(new CustomEvent('open', { bubble: true, detail: url }))
                    },
                }"
                class="flex flex-col divide-y"
                {{ $attributes->wire('remove') }}
                {{ $attributes->wire('sorted') }}
            >
                @if ($accept === 'image/*')
                    <div x-ref="sortable" class="flex items-center gap-4 flex-wrap p-4 bg-white">
                        @foreach ($files as $file)
                            <div class="rounded-lg" data-sortable-id="{{ $file->id }}">
                                <x-thumbnail :file="$file" x-on:click.stop="preview('{{ $file->url }}')">
                                    <x-slot:buttons>
                                        @if ($multiple)
                                            <div 
                                                x-on:click.stop="select(@js($file->id))"
                                                x-bind:class="checkboxes.includes(@js($file->id)) ? 'text-green-500' : 'text-white'" 
                                                class="cursor-pointer"
                                            >
                                                <x-icon name="circle-check"/>
                                            </div>
                                        @else
                                            <div x-on:click.stop="remove" class="cursor-pointer text-white">
                                                <x-icon name="remove"/>
                                            </div>
                                        @endif
                                    </x-slot:buttons>
                                </x-thumbnail>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div x-ref="sortable" class="flex flex-col divide-y bg-white">
                        @foreach ($files as $file)
                            <div class="py-2 px-4 flex items-center gap-3" data-sortable-id="{{ $file->id }}">
                                @if ($file->is_image)
                                    <figure class="shrink-0 w-8 h-8 rounded-lg bg-gray-100 overflow-hidden">
                                        <img src="{{ $file->url }}" class="w-full h-full object-cover">
                                    </figure>
                                @else
                                    <x-icon name="file" class="text-gray-400 shrink-0"/>
                                @endif
                                <div class="grow truncate">{{ $file->name }}</div>
                                <div x-on:click.stop="checkboxes = [{{ $file->id }}]; remove()" class="shrink-0">
                                    <x-close color="red"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($multiple)
                    <div x-show="checkboxes.length" class="p-4 flex items-center gap-2 flex-wrap">
                        <div class="grow flex items-center gap-2">
                            <x-button x-on:click="checkboxes = {{ json_encode($value) }}" label="Select All" color="gray" outlined/>
                            <x-button x-on:click="checkboxes = []" label="Clear" color="gray" outlined/>
                        </div>

                        <x-button label="Remove" color="red" x-on:click="$dispatch('confirm', {
                            title: '{{ __('Remove Files') }}',
                            message: '{{ __('Are you sure to REMOVE the selected files?') }}',
                            type: 'error',
                            onConfirmed: () => remove(),
                        })"/>
                    </div>
                @endif

                <x-form.file.preview :id="$id.'-preview'"/>
            </div>
        @endif
        
        <div 
            x-data="{
                dropzone: false,
                uploading: false,
                upload (files) {
                    this.dropzone = false
                    document.querySelector(@js('#'.$id.'-uploader')).dispatchEvent(
                        new CustomEvent('upload', { bubble: false, detail: files })
                    )
                },
                browse () {
                    document.querySelector('#{{ $id.'-library'}}').dispatchEvent(
                        new CustomEvent('open', { bubble: false })
                    )
                },
                input (files) {
                    this.uploading = false

                    const id = config.multiple ? files.map(file => (file.id)) : files[0].id

                    if (config.model) {
                        if (config.multiple) {
                            const value = (this.$wire.get(config.model) || []).concat(id)
                            const unique = [...new Set(value)]
                            this.$wire.set(config.model, unique)
                        }
                        else this.$wire.set(config.model, id)
                    }
                }
            }"
            x-show="!checkboxes.length"
            x-on:dropped="upload($event.detail)"
            x-on:uploaded="input($event.detail)"
            x-on:uploading="uploading = true"
            id="uploader"
        >
            @if ($upload)
                <x-form.file.uploader :id="$id.'-uploader'" 
                    :accept="$accept"
                    :location="$location"
                    :visibility="$visibility"
                />
            @endif

            <div x-show="!uploading" class="p-4 flex items-center gap-2 flex-wrap">
                @if ($library)
                    <x-button icon="folder-open" color="gray" outlined
                        x-on:click="browse"
                        label="Browse Library"
                    />
                @endif
            
                @if ($upload)
                    <input x-ref="input"
                        x-on:change="upload($event.target.files)"
                        x-on:input.stop
                        type="file" 
                        accept="{{ $accept }}" 
                        class="hidden" 
                        {{ $multiple ? 'multiple' : null }}
                    >

                    <x-button x-on:click="$refs.input.click()" label="Upload File" color="gray" outlined/>

                    <x-form.file.dropzone class="grow"/>    
                @endif
            </div>

            @if ($library)
                <div x-on:input="input($event.detail)">
                    <x-form.file.library 
                        :id="$id.'-library'"
                        :accept="$accept" 
                        :multiple="$multiple"
                        :header="'Select '.component_label($attributes)"
                    />
                </div>
            @endif
        </div>
    </div>
</x-form.field>