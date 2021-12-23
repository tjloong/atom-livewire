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
     * 
     * @return void
     */
    public function __construct(
        $url,
        $mime = null
    ) {
        $this->type = $this->getType($mime);
        $this->url = $this->getUrl($url);
    }

    /**
     * Render component
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::components.file-card');
    }

    /**
     * Get file type
     * 
     * @return string
     */
    private function getType($mime)
    {
        if ($mime === 'youtube') return 'youtube';
        else if (Str::startsWith($mime, 'image/')) return 'image';
        else if (Str::startsWith($mime, 'video/')) return 'video';
        else if (Str::endsWith($mime, 'pdf')) return 'pdf';
        else return 'file';
    }

    private function getUrl($url)
    {
        if ($this->type === 'youtube') {
            $vid = Str::replace('https://www.youtube.com/embed/', '', $url);
            return 'https://img.youtube.com/vi/' . $vid . '/default.jpg';
        }

        return $url;
    }
}