<?php

namespace Jiannius\Atom\Http\Livewire\App\File\Uploader;

use Livewire\Component;

class WebImage extends Component
{
    public $urls = [];
    public $text = null;
    public $multiple;

    /**
     * Updated text
     */
    public function updatedText()
    {
        $this->urls = collect(explode("\n", $this->text))->filter()->values()->all();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $completed = [];

        foreach ($this->urls as $url) {
            array_push($completed, model('file')->storeImageUrl($url));
        }

        $this->urls = [];
        $this->text = null;

        $this->emitUp('completed', $completed);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.file.uploader.web-image');
    }
}