<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Richtext extends Component
{
    public $uid;
    public $toolbar;

    /**
     * Contructor
     */
    public function __construct(
        $uid = null,
        $toolbar = null
    ) {
        $this->uid = $uid ?? 'richtext-'.uniqid();
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