<?php

namespace Jiannius\Atom\Traits\Livewire;

use Livewire\WithFileUploads;

trait WithFile
{
    use WithFileUploads;

    public $upload = [
        'file' => null,
        'location' => null,
        'visibility' => 'public',
    ];

    /**
     * Updated upload file
     */
    public function updatedUploadFile()
    {
        $upload = data_get($this->upload, 'file');
        $location = data_get($this->upload, 'location');
        $visibility = data_get($this->upload, 'visibility');
        $filename = $upload->getFilename();

        $file = model('file')->store($upload, $location, $visibility);

        $this->dispatchBrowserEvent('upload-completed', [
            'filename' => $filename,
            'file_id' => $file->id,
        ]);
    }

    /**
     * Get files
     */
    public function getFiles($data)
    {
        $filters = data_get($data, 'filters');
        $page = data_get($data, 'page');

        if (is_null($filters)) {
            return model('file')->whereIn('id', (array)$data)->latest('id')->get()->toArray();
        }
        else {
            $paginator = model('file')->filter($filters)->latest('id')->toPage($page ?? 1, 100);
            return $paginator->items();
        }
    }

    /**
     * Delete file
     */
    public function deleteFile($id)
    {
        optional(model('file')->find($id))->delete();
    }

    /**
     * Load file urls
     */
    public function loadFileUrls($urls = [], $config)
    {
        $urls = collect($urls)
            ->filter()
            ->map(function($val) use ($config) {
                $youtubeId = data_get($config, 'youtube') ? youtube_vid($val) : null;
                $youtubeTn = $youtubeId ? 'https://img.youtube.com/vi/'.$youtubeId.'/default.jpg' : null;
                $imageTn = data_get($config, 'image') ? $val : null;

                if ($youtubeTn) return ['tn' => $youtubeTn, 'url' => $val];
                elseif ($imageTn) return ['tn' => $imageTn, 'url' => $val];
            })
            ->filter()
            ->values()
            ->all();

        return $urls;
    }

    /**
     * Add file urls
     */
    public function addFileUrls($urls)
    {
        $urls = collect($urls)->map(function($url) {
            $file = model('file')->store($url);
            return $file->id;
        });

        return $urls->values()->all();
    }
}