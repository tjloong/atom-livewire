@props([
    'uid' => component_id($attributes, 'richtext'),
])

<x-form.field {{ $attributes }}>
    <div 
        wire:ignore 
        x-data="{
            uid: @js($uid),
            file: false,
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            loading: false,
            toolbar: @js($toolbar),
            placeholder: @js($attributes->get('placeholder') ?? __('Your content...')),

            init () {
                ClassicEditor
                .create(this.$refs.ckeditor, { 
                    placeholder: this.placeholder, 
                    toolbar: this.toolbar,
                })
                .then(editor => {
                    // initial content
                    if (this.value) editor.setData(this.value)

                    // onchange update
                    editor.model.document.on('change:data', () => {
                        this.value = editor.getData()
                        this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('input', { bubble: true })))
                    })
                    
                    // insert media
                    editor.ui.view.toolbar.on('insert-media:click', () => {
                        this.$el.querySelector('#file-library')?.dispatchEvent(new Event('open'))

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

                            window.removeEventListener('media', insert)
                        }

                        window.addEventListener('media', insert)
                    })
                })
            },
        }"
        class="{{ $attributes->get('class') }}"
    >
        <div x-ref="ckeditor" x-show="!loading"></div>
        <div x-on:input="$dispatch('media', $event.detail)">
            <x-form.file.library accept="image/*"/>
        </div>
    </div>
</x-form.field>
