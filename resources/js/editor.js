import { Editor, Extension, mergeAttributes } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import { Color } from '@tiptap/extension-color'
import Highlight from '@tiptap/extension-highlight'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import Placeholder from '@tiptap/extension-placeholder'
import Subscript from '@tiptap/extension-subscript'
import Superscript from '@tiptap/extension-superscript'
import Table from '@tiptap/extension-table'
import TableCell from '@tiptap/extension-table-cell'
import TableHeader from '@tiptap/extension-table-header'
import TableRow from '@tiptap/extension-table-row'
import TextAlign from '@tiptap/extension-text-align'
import TextStyle from '@tiptap/extension-text-style'
import Underline from '@tiptap/extension-underline'
import Youtube from '@tiptap/extension-youtube'

// extend table
const TableExtended = Table.extend({
    renderHTML ({ node, HTMLAttributes }) {
        return ['div', { class: 'table-wrapper' }, ['table', mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), ['tbody', 0]]]
    },
})

// extend table cell
const TableCellExtended = TableCell.extend({
    renderHTML ({ node, HTMLAttributes }) {
        let totalwidth = 0

        if (HTMLAttributes.colwidth) {
            HTMLAttributes.colwidth.forEach(width => totalwidth += (width || 0))
        }

        if (totalwidth && totalwidth > 0) HTMLAttributes.style = `width: ${totalwidth}px`

        return ['td', mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0]
    },
})

// font size extension
const FontSize = Extension.create({
    name: 'fontSize',
    addOptions () {
        return {
            types: ["textStyle"],
        };
    },
    addGlobalAttributes () {
        return [{
            types: this.options.types,
            attributes: {
                fontSize: {
                    default: null,
                    parseHTML: element => element.getAttribute('data-font-size'),
                    renderHTML: (attributes) => {
                        if (!attributes.fontSize) return {}

                        const sizes = {
                            xs: 'text-xs',
                            sm: 'text-sm',
                            md: 'text-base',
                            lg: 'text-lg',
                            xl: 'text-xl',
                        }

                        if (sizes[attributes.fontSize]) {
                            return { 
                                'data-font-size': attributes.fontSize,
                                class: sizes[attributes.fontSize],
                            }
                        }
                        else {
                            return { 
                                'data-font-size': attributes.fontSize,
                                style: `font-size: ${attributes.fontSize}`,
                            }
                        }
                    },
                },
            },
        }]
    },
    addCommands () {
        return {
            setFontSize: (fontSize) => ({ chain }) => {
                return chain().setMark("textStyle", { fontSize }).run()
            },
            unsetFontSize: () => ({ chain }) => {
                return chain()
                    .setMark("textStyle", { fontSize: null })
                    .removeEmptyTextStyle()
                    .run();
            },
        };
    },
})

// extend image
const ImageExtended = Image.extend({
    addAttributes () {
        return {
            ...this.parent?.(),
            float: {
                default: null,
                parseHTML: element => element.getAttribute('data-float'),
                renderHTML: attributes => (attributes.float ? { 
                    'data-float': attributes.float,
                    style: `float: ${attributes.float}`,
                } : {}),
            },
            align: {
                default: null,
                parseHTML: element => element.getAttribute('data-align'),
                renderHTML: attributes => {
                    let style

                    if (attributes.align === 'left') style = `margin-right: auto`
                    else if (attributes.align === 'center') style = `margin-left: auto; margin-right: auto`
                    else if (attributes.align === 'right') style = `margin-left: auto`

                    return style ? {
                        'data-align': attributes.align,
                        style,
                    } : {}
                },
            },
            width: {
                default: null,
                parseHTML: element => element.getAttribute('data-width'),
                renderHTML: attributes => (attributes.width ? {
                    'data-width': attributes.width,
                    style: `width: ${attributes.width}`,
                } : {}),
            },
        }
    },
})

window.setupEditor = (content, placeholder = 'Write something...') => {
    let editor

    return {
        content,
        focus: false,
  
        init(element) {
            this.initEditor(element)
            this.$watch('content', (content) => {
                if (content === editor.getHTML()) return
                editor.commands.setContent(content, false)
            })
        },

        initEditor (element) {
            editor = new Editor({
                element,
                content: this.content,
                extensions: [
                    Color,
                    FontSize,
                    Highlight.configure({ multicolor: true }),
                    ImageExtended.configure({ allowBase64: true }),
                    Link.configure({ openOnClick: false }),
                    Placeholder.configure({ placeholder }),
                    Subscript,
                    Superscript,
                    StarterKit,
                    TableExtended.configure({ resizable: true }),
                    TableCellExtended,
                    TableRow,
                    TableHeader,
                    TextAlign.configure({ types: ['heading', 'paragraph'] }),
                    TextStyle,
                    Underline,
                    Youtube,
                ],
                editorProps: {
                    attributes: {
                        class: 'editor-content m-4 focus:outline-none',
                    },
                },
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

        editor () {
            return editor
        },

        can () {
            return editor.can()
        },

        commands () {
            editor.chain().focus()
            return editor.commands
        },
    }
}