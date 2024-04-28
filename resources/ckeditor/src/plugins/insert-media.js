import imageIcon from '@ckeditor/ckeditor5-core/theme/icons/image.svg';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';

export default class InsertMedia {
    constructor (editor) {
        editor.ui.componentFactory.add('insertMedia', locale => {
            const view = new ButtonView(locale)

            view.set({
                label: 'Insert Media',
                icon: imageIcon,
                tooltip: true,
            })

            view.on('execute', () => editor.ui.view.toolbar.fire('insert-media:click'))

            return view
        })
    }
}
