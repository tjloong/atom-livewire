export default (toolbar, placeholder) => ({
    value: null,
    file: false,
    loading: false,

    init () {
        this.loading = true
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
                // update value
                this.value = editor.getData() || null
                editor.model.document.on('change:data', () => this.value = editor.getData() || null)
                
                // insert image
                editor.ui.view.toolbar.on('image:click', () => {
                    const uid = this.$el.getAttribute('data-uid')

                    this.$dispatch(`file-manager-${uid}-open`)
                    window.addEventListener(`file-manager-${uid}-completed`, (event) => {
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