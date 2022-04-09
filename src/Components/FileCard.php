<?php

namespace Jiannius\Atom\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class FileCard extends Component
{
    public $url;
    public $type;

    /**
     * Constructor
     */
    public function __construct(
        $url,
        $mime = null
    ) {
        $this->type = $this->getType($mime);
        $this->url = $this->getUrl($url);
    }

    /**
     * Get file type
     */
    public function getType($mime)
    {
        if ($mime === 'youtube') return 'youtube';
        else if (Str::startsWith($mime, 'image/')) return 'image';
        else if (Str::startsWith($mime, 'video/')) return 'video';
        else if (Str::endsWith($mime, 'pdf')) return 'pdf';
        else return 'file';
    }

    /**
     * Get url
     */
    public function getUrl($url)
    {
        if ($this->type === 'youtube') {
            $vid = Str::replace('https://www.youtube.com/embed/', '', $url);
            return 'https://img.youtube.com/vi/' . $vid . '/default.jpg';
        }

        return $url;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('atom::components.file-card');
    }
}