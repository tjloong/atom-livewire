<?php

namespace Jiannius\Atom\Actions;

class UploadFile
{
    public function __construct(public $params)
    {
        //
    }

    public function run()
    {
        if ($files = $this->saveUrls()) return $files;
        if ($files = $this->saveFiles()) return $files;

        return [];
    }

    public function saveUrls()
    {
        return collect(get($this->params, 'url'))
            ->filter()
            ->map(fn ($url) => model('file')->store(content: $url))
            ->values()
            ->all();
    }

    public function saveFiles()
    {
        $path = get($this->params, 'path');
        $visibility = get($this->params, 'visibility');
        $uploads = get($this->params, 'files');

        $files = collect($uploads)
            ->map(fn($upload) => model('file')->store(
                content: $upload,
                path: $path,
                visibility: $visibility,
            ))
            ->map(fn($file) => $file->toArray());

        return $files->toArray();
    }
}
