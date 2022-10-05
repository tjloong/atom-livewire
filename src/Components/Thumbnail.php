<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class Thumbnail extends Component
{
    public $icon;
    public $video;
    public $image;
    public $square;
    public $youtube;

    /**
     * Constructor
     */
    public function __construct(
        $file = null, 
        $url = null,
        $icon = null,
        $youtube = null,
        $video = null,
        $square = true,
    ) {
        if (optional($file)->type === 'youtube') $this->youtube = 'https://img.youtube.com/vi/'.data_get($file->data, 'vid').'/default.jpg';
        else if (optional($file)->is_video) $this->video = $file->url;
        else if (optional($file)->is_image) $this->image = $file->url;
        else if (optional($file)->is_audio) $this->icon = 'music';
        else if (optional($file)) $this->icon = 'file';
        else {
            $this->icon = $icon;
            $this->image = $url;
            $this->youtube = $youtube;
            $this->video = $video;
        }

        $this->square = $square;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.thumbnail');
    }
}