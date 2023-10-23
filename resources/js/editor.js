import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'

window.setupEditor = function (content) {
    let editor

    return {
        focus: false,
        content: content,
  
        init(element) {
            this.initEditor(element)
            this.$watch('content', (content) => {
                if (content === editor.getHTML()) return
                editor.commands.setContent(content, false)
            })
        },

        initEditor (element) {
            editor = new Editor({
                element: element,
                extensions: [
                    StarterKit,
                ],
                editorProps: {
                    attributes: {
                        class: 'editor-content m-5 focus:outline-none',
                    },
                },
                content: this.content,
                onFocus: ({ editor, event }) => {
                    this.focus = true
                },
                onBlur: ({ editor, event }) => {
                    this.focus = false
                },
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML()
                },
            })
        },

        isLoaded () {
            return editor
        },

        isActive (type, opts = {}) {
            return editor.isActive(type, opts)
        },

        canUndo () {
            return editor.can().undo()
        },

        canRedo () {
            return editor.can().redo()
        },

        undo () {
            editor.chain().focus().undo().run()
        },

        redo () {
            editor.chain().focus().redo().run()
        },

        setParagraph() {
            editor.chain().setParagraph().focus().run()
        },
        
        toggleHeading(opts) {
            editor.chain().toggleHeading(opts).focus().run()
        },

        toggleBold () {
            editor.chain().toggleBold().focus().run()
        },

        toggleItalic () {
            editor.chain().toggleItalic().focus().run()
        },

        toggleStrike () {
            editor.chain().toggleStrike().focus().run()
        },

        toggleBlockquote () {
            editor.chain().toggleBlockquote().focus().run()
        },

        toggleBulletList () {
            editor.chain().toggleBulletList().focus().run()
        },

        toggleOrderedList () {
            editor.chain().toggleOrderedList().focus().run()
        },

        sinkListItem () {
            editor.chain().focus().sinkListItem('listItem').run()
        },

        liftListItem () {
            editor.chain().focus().liftListItem('listItem').run()
        },
    }
}