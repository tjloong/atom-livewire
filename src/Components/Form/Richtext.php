<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Richtext extends Component
{
    public $toolbar;

    /**
     * Contructor
     */
    public function __construct($toolbar = null) {
        $this->toolbar = $toolbar
            ? (is_array($toolbar) ? $toolbar : explode(',', $toolbar))
            : [
                'heading',
                '|', 'bold', 'italic', 'underline', 'fontSize', 'fontColor', 'link', 'bulletedList', 'numberedList',
                '|', 'alignment', 'outdent', 'indent', 'horizontalLine',
                '|', 'blockQuote', 'insertMedia', 'mediaEmbed', 'insertTable', 'undo', 'redo',
                '|', 'sourceEditing',
            ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.richtext');
    }
}