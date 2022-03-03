/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

// The editor creator to use.
import ClassicEditorBase from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';

import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import FontSize from '@ckeditor/ckeditor5-font/src/fontsize';
import FontColor from '@ckeditor/ckeditor5-font/src/fontcolor';
import GeneralHtmlSupport from '@ckeditor/ckeditor5-html-support/src/generalhtmlsupport';
import Heading from '@ckeditor/ckeditor5-heading/src/heading';
import HorizontalLine from '@ckeditor/ckeditor5-horizontal-line/src/horizontalline';
import Image from '@ckeditor/ckeditor5-image/src/image';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import Indent from '@ckeditor/ckeditor5-indent/src/indent';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import Link from '@ckeditor/ckeditor5-link/src/link';
import List from '@ckeditor/ckeditor5-list/src/list';
import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import PasteFromOffice from '@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting';
import Table from '@ckeditor/ckeditor5-table/src/table';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar';
import TextTransformation from '@ckeditor/ckeditor5-typing/src/texttransformation';
import './styles.css';

export default class ClassicEditor extends ClassicEditorBase {}

/**
 * Custom insert image plugin
 */
import imageIcon from '@ckeditor/ckeditor5-core/theme/icons/image.svg';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';

class InsertMedia {
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

// Plugins to include in the build.
ClassicEditor.builtinPlugins = [
    Essentials,
    Alignment,
    Autoformat,
    Bold,
    BlockQuote,
    FontSize,
    FontColor,
    GeneralHtmlSupport,
    Heading,
    HorizontalLine,
    Image,
    ImageResize,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    Indent,
    InsertMedia,
    Italic,
    Link,
    List,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    SourceEditing,
    Table,
    TableToolbar,
    TextTransformation,
];

// Editor configuration.
ClassicEditor.defaultConfig = {
    image: {
        toolbar: [
            'imageStyle:alignLeft',
            'imageStyle:alignRight',
            '|',
            'imageStyle:alignBlockLeft',
            'imageStyle:alignCenter',
            'imageStyle:alignBlockRight',
            '|',
            'toggleImageCaption',
            'imageTextAlternative'
        ],
    },
    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells'
        ]
    },
    htmlSupport: {
        allow: [
            {
                name: 'video',
                attributes: true,
                classes: true,
                styles: true,
            },
            {
                name: 'audio',
                attributes: true,
                classes: true,
                styles: true,
            },
        ],
    },
};
