<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot name="label">{{ $slot }}</x-slot>
    @endif

    <div wire:ignore x-data="{ value: $wire.get('{{ $attributes->wire('model')->value() }}') }">  
        <div x-data="richtextInput(@js($toolbar), '{{ $attributes->get('placeholder') }}')" class="{{ $attributes->get('class') }}">
            <div {{ $attributes }}>
                <textarea x-ref="input" x-on:change="$dispatch('input', $event.target.value)" class="hidden"></textarea>
            </div>

            <div x-show="loading" class="h-80 p-4">
                <div class="flex items-center">
                    <x-loader/>
                    <div class="font-medium">Loading Editor</div>
                </div>
            </div>

            <div x-ref="ckeditor" x-show="!loading"></div>

            @livewire('atom.file.uploader', [
                'uid' => 'richtext-uploader',
                'title' => 'Insert Media',
                'accept' => ['image', 'video', 'audio', 'youtube'],
                'sources' => ['device', 'image', 'youtube', 'library'],
            ], key(uniqid()))
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('richtextInput', (toolbar, placeholder) => ({
                uid: null,
                file: false,
                loading: false,
    
                init () {
                    this.loading = true
                    ScriptLoader.load('/js/ckeditor5/ckeditor.js')
                        .then(() => this.createEditor())
                        .finally(() => this.loading = false)
                },
    
                createEditor () {
                    const defaultToolbar = [
                        'heading',
                        '|', 'bold', 'italic', 'fontSize', 'fontColor', 'link', 'bulletedList', 'numberedList',
                        '|', 'alignment', 'outdent', 'indent', 'horizontalLine',
                        '|', 'blockQuote', 'insertMedia', 'insertTable', 'undo', 'redo',
                        '|', 'sourceEditing',
                    ]
    
                    ClassicEditor.create(this.$refs.ckeditor, {
                        placeholder: placeholder || 'Content goes here',
                        toolbar: toolbar || defaultToolbar,
                    }).then(editor => {
                        // initial content
                        if (this.value) editor.setData(this.value)
    
                        // onchange update
                        editor.model.document.on('change:data', () => {
                            this.$refs.input.value = editor.getData()
                            this.$refs.input.dispatchEvent(new Event('change'))
                        })
                            
                        // insert media
                        editor.ui.view.toolbar.on('insert-media:click', () => {
                            this.$dispatch(`richtext-uploader-open`)
    
                            const insert = (event) => {
                                const files = event.detail
    
                                files.forEach(file => {
                                    if (file.is_image) {
                                        editor.model.change(writer => {
                                            const imageElement = writer.createElement('imageBlock', { src: file.url })
                                            editor.model.insertContent(imageElement, editor.model.document.selection);
                                        })
                                    }
                                    else if (file.is_video) {
                                        const html = `<video controls class="w-full min-h-[300px]"><source src="${file.url}" type="${file.mime}"></video>`
                                        const viewFragment = editor.data.processor.toView(html)
                                        const modelFragment = editor.data.toModel(viewFragment)
    
                                        editor.model.insertContent(modelFragment)
                                    }
                                    else if (file.is_audio) {
                                        const html = `<audio controls class="w-full"><source src="${file.url}" type="${file.mime}"></audio>`
                                        const viewFragment = editor.data.processor.toView(html)
                                        const modelFragment = editor.data.toModel(viewFragment)
    
                                        editor.model.insertContent(modelFragment)
                                    }
                                    else if (file.type === 'youtube') {
                                        editor.execute('mediaEmbed', file.url)
                                    }
                                })
    
                                window.removeEventListener(`richtext-uploader-completed`, insert)
                            }
    
                            window.addEventListener(`richtext-uploader-completed`, insert)
                        })
                    })
                },
            }))
        })
    </script>
</x-input.field>

