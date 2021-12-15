export default (toolbar, placeholder) => ({
    uid: null,
    file: false,
    loading: false,

    init () {
        this.loading = true
        this.uid = this.$el.getAttribute('data-uid')
        ScriptLoader.load('/js/ckeditor5/ckeditor.js')
            .then(() => this.createEditor())
            .finally(() => this.loading = false)
    },

    createEditor () {
        InlineEditor
            .create(this.$refs.ckeditor, {
                placeholder: placeholder || 'Content goes here',
                toolbar: toolbar || [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'fontSize',
                    'fontColor',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'blockQuote',
                    'insertImage',
                    'mediaEmbed',
                    'insertTable',
                    'undo',
                    'redo'
                ],
            })
            .then(editor => {
                // initial content
                if (this.value) editor.setData(this.value)

                // onchange update
                editor.model.document.on('change:data', () => {
                    this.$refs.input.value = editor.getData()
                    this.$refs.input.dispatchEvent(new Event('change'))
                })
                
                // insert image
                editor.ui.view.toolbar.on('image:click', () => {
                    this.$dispatch(`file-manager-${this.uid}-open`)
                    window.addEventListener(`file-manager-${this.uid}-completed`, (event) => {
                        const files = event.detail

                        files.forEach(file => {
                            editor.model.change(writer => {
                                const imageElement = writer.createElement('imageBlock', { src: file.url })
                                editor.model.insertContent(imageElement, editor.model.document.selection);
                            })
                        })
                    })
                })
            })
    },
})