<?php

namespace Jiannius\Atom\Traits\Livewire;

use Livewire\WithFileUploads;

trait WithFileInput
{
    use WithFileUploads;

    public $fileInputUploads = [];

    // updated file input uploads
    public function updatedFileInputUploads($val, $attr) : void
    {
        [$model, $attr] = explode('.', $attr);

        if ($attr === 'files') {
            $path = data_get($this->fileInputUploads, $model.'.path');
            $visibility = data_get($this->fileInputUploads, $model.'.visibility');

            $uploads = collect(data_get($this->fileInputUploads, $model.'.files'))
                ->map(function($upload) use ($path, $visibility) {
                    $file = model('file')->store($upload, $path, $visibility);
                    return $file->toArray();
                })
                ->toArray();

            $this->emit('filesUploaded', [$model => $uploads]);
        }
    }

    // get files for library
    public function getFilesForLibrary($data)
    {
        $filters = data_get($data, 'filters');
        $page = data_get($data, 'page');

        if (is_null($filters)) {
            return model('file')->whereIn('id', (array) $data)->latest('id')->get()->toArray();
        }
        else {
            $paginator = model('file')->filter($filters)->latest('id')->toPage($page ?? 1, 100);
            return $paginator->items();
        }
    }

    // create files from urls
    public function createFilesFromUrls($urls)
    {
        return collect($urls)
            ->map(fn($url) => model('file')->store($url))
            ->values()
            ->all();
    }
}