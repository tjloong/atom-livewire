<?php

namespace Jiannius\Atom\Http\Livewire\App\File\Uploader;

use Livewire\Component;

class Youtube extends Component
{
    public $text;
    public $urls = [];
    public $multiple;

    /**
     * Updated text
     */
    public function updatedText()
    {
        $this->urls = collect(explode("\n", $this->text))->filter()->map(function($val) {
            $vid = youtube_vid($val);

            return [
                'vid' => $vid,
                'tn' => $vid ? 'https://img.youtube.com/vi/'.$vid.'/default.jpg' : null,
                'valid' => $vid ? true : false,
            ];
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $completed = [];

        foreach ($this->urls as $url) {
            array_push($completed, model('file')->storeYoutubeUrl($url['vid']));
        }

        $this->emitUp('completed', $completed);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.file.uploader.youtube');
    }
}