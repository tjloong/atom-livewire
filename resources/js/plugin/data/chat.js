export default (config) => {
    return {
        upload: {
            files: [],
            uploading: false,
            progress: null,
            ...config.upload,

            run () {
                return new Promise((resolve, reject) => {
                    if (!this.files.length) resolve()
                    else {
                        this.uploading = true

                        Atom.upload(this.files, {
                            max: this.max,
                            accept: this.accept,
                            multiple: this.multiple,
                            progress: (value) => this.progress = value,
                        })
                            .then(res => {
                                this.files = []
                                resolve(res.id)
                            })
                            .catch(({ message }) => {
                                Atom.alert({ title: tr('app.label.unable-to-upload'), message }, 'error')
                                reject()
                            })
                            .finally(() => this.uploading = false)
                    }
                })
            },
        },

        editor () {
            return this.$refs.editor?.editor
        },

        paste (e) {
            let clipboard = e.clipboardData
            let files = Array.from(clipboard.items).filter(item => (item.kind === 'file')).map(item => (item.getAsFile()))
            let text = clipboard.getData('text')

            if (files.length) this.attach(files)
            else if (text) this.editor().chain().focus().insertContent(text).run()
        },

        drop (e) {
            let files = e.dataTransfer.files
            this.attach(files)
        },

        attach (files) {
            if (!files || !files.length) return

            Array.from(files).forEach(file => {
                file.src = file.type.startsWith('image/') ? URL.createObjectURL(file) : null
            })

            this.upload.files = [...this.upload.files, ...files]

            this.$nextTick(() => this.editor().commands.focus())
        },

        createEditor (config) {
            Editor({
                element: this.$refs.editor,
                tiptapConfig: {
                    placeholder: config.placeholder,
                    autofocus: false,
                    editorProps: {
                        attributes: {
                            class: 'editor-content editor-chat-content',
                        },
                        // disable pasting and handle using x-on:paste
                        transformPasted () {
                            return ''
                        },
                        // disable drop and handle using x-on:drop
                        handleDrop () {
                            return true
                        },
                    },
                },
                disableEnterKey: true,
                mentionTemplate: this.$root.querySelector('.editor-mention'),
            })
        },

        scroll () {
            if (!this.$refs.conversation) return
            this.$refs.conversation.scrollTop = this.$refs.conversation.scrollHeight
        },

        submit () {
            if (this.upload.uploading) return

            let content = this.editor().getHTML()
            content = content.replace(new RegExp('<p></p>$'), '');

            if (empty(content) && !this.upload.files.length) return

            this.editor().setEditable(false)

            this.upload.run()
                .then(files => this.$dispatch('submit-chat', { content, files }))
                .then(() => this.editor().chain().setContent('', false).focus().run())
                .then(() => this.editor().setEditable(true))
        },
    }
}