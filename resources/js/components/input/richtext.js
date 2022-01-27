export default (toolbar, placeholder) => ({
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
            '|', 'outdent', 'indent',
            '|', 'blockQuote', 'insertMedia', 'insertTable', 'undo', 'redo',
        ]

        InlineEditor.create(this.$refs.ckeditor, {
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
})