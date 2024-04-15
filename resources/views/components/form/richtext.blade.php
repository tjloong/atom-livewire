<x-form.field {{ $attributes }}>
    <div 
        wire:ignore 
        x-data="{
            content: @entangle($attributes->wire('model')),
            loading: false,
            toolbar: @js($toolbar),
            placeholder: @js(tr($attributes->get('placeholder', 'Your content...'))),
            startEditor () {
                ClassicEditor.create(this.$refs.ckeditor, { 
                    placeholder: this.placeholder, 
                    toolbar: this.toolbar,
                }).then(editor => {
                    // initial content
                    if (this.content) editor.setData(this.content)

                    // onchange update
                    editor.model.document.on('change:data', () => this.content = editor.getData())
                    
                    // insert media
                    editor.ui.view.toolbar.on('insert-media:click', () => {
                        const insert = (event) => {
                            const files = [event.detail].flat()

                            files.forEach(file => {
                                if (file.is_image) {
                                    editor.model.change(writer => {
                                        const imageElement = writer.createElement('imageBlock', { src: file.url })
                                        editor.model.insertContent(imageElement, editor.model.document.selection);
                                    })
                                }
                                else if (file.is_video) {
                                    const html = `{{ '<video controls class="w-full min-h-[300px]"><source src="${file.url}" type="${file.mime}"></video>' }}`
                                    const viewFragment = editor.data.processor.toView(html)
                                    const modelFragment = editor.data.toModel(viewFragment)

                                    editor.model.insertContent(modelFragment)
                                }
                                else if (file.is_audio) {
                                    const html = `{{ '<audio controls class="w-full"><source src="${file.url}" type="${file.mime}"></audio>' }}`
                                    const viewFragment = editor.data.processor.toView(html)
                                    const modelFragment = editor.data.toModel(viewFragment)

                                    editor.model.insertContent(modelFragment)
                                }
                                else if (file.type === 'youtube') {
                                    editor.execute('mediaEmbed', file.url)
                                }
                            })

                            window.removeEventListener('files-selected', insert)
                        }

                        window.addEventListener('files-selected', insert)

                        Livewire.emit('showFilesLibrary', { accept: 'image/*', multiple: false })
                    })
                })
            },
        }"
        x-init="startEditor()"
        class="{{ $attributes->get('class') }}">
        <div x-ref="ckeditor" x-show="!loading"></div>
    </div>
</x-form.field>
