<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function upload() : mixed
    {
        if ($url = request()->url) {
            return collect($url)
                ->map(fn($url) => model('file')->store(content: $url))
                ->values()
                ->all();
        }
        else {
            $path = request()->path;
            $visibility = request()->visibility;
            $uploads = request()->file('files');
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
}