export default (config) => {
    return {
        files: [],
        uploading: false,
        uploadConfig: config.upload,

        upload () {
            if (!this.files.length || !this.uploadConfig.route) return new Promise((resolve) => resolve())
                
            this.uploading = true

            let job = this.files.firstWhere('done', false)

            if (job) {
                let formdata = new FormData()
                formdata.append('file', job.file)

                return Atom.ajax(this.uploadConfig.route).post(formdata).then(res => {
                    Object.assign(job, { done: true, response: res })
                    return this.upload()
                })
            }
            else {
                let res = this.files.map(file => (file.response))
                this.files = []
                this.uploading = false
                return new Promise((resolve) => resolve(res))
            }
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

            this.files = [
                ...this.files,
                ...Array.from(files).map(file => ({
                    file,
                    src: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
                    done: false,
                })),
            ]

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
            if (this.uploading) return

            let content = this.editor().getHTML()
            content = content.replace(new RegExp('<p></p>$'), '');

            if (empty(content) && !this.files.length) return

            if (this.uploadConfig.route) {
                this.editor().setEditable(false)
                this.upload()
                    .then(files => this.$dispatch('submit-chat', { content, files }))
                    .then(() => this.editor().chain().setContent('', false).focus().run())
                    .then(() => this.editor().setEditable(true))
            }
            else {
                this.$dispatch('submit-chat', { content, files: this.files })
            }
        },
    }
}